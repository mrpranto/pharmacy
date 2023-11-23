<?php

namespace App\Services;

use App\Models\People\Customer;
use App\Models\Purchase\Purchase;
use App\Models\Sale\Sale;
use App\Models\trait\FileHandler;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardServices extends BaseServices
{
    public $sale;
    public $purchase;
    public $customer;
    public function __construct(Sale $sale, Purchase $purchase, Customer $customer)
    {
        $this->sale = $sale;
        $this->purchase = $purchase;
        $this->customer = $customer;
    }

    use FileHandler;

    /**
     * @param $request
     * @return $this
     */
    public function validateProfileUpdate($request): static
    {
        $request->validate([
            "name" => "required|string",
            "email" => "required|email|unique:users,email," . auth()->id(),
            "phone_number" => "required|numeric|unique:users,phone_number," . auth()->id(),
            "gender" => "required|in:male,female",
            "address" => "nullable|string",
            "profile_picture" => 'nullable|image|max:2048',
        ]);

        return $this;
    }


    /**
     * @param $request
     * @return RedirectResponse
     */
    public function updateProfile($request): RedirectResponse
    {
        try {

            DB::transaction(function () use ($request) {

                $user = Auth::user();

                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'gender' => $request->gender,
                    'address' => $request->address,
                ]);

                if ($request->has('profile_picture')) {
                    $this->uploadProfilePicture($request->file('profile_picture'), $user);
                }
            });

            return redirect()->back()->with('success', __t('profile_update'));

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * @param $profile_picture
     * @param $user
     * @return void
     */
    public function uploadProfilePicture($profile_picture, $user): void
    {
        $this->deleteImage(optional($user->profilePicture)->path);

        $file_path = $this->uploadImage($profile_picture, User::PROFILE_PICTURE_TYPE);

        $user->profilePicture()->updateOrCreate([
            'type' => User::PROFILE_PICTURE_TYPE
        ], [
            'path' => $file_path
        ]);
    }

    /**
     * Dashboard counter.
     *
     * @return array
     */
    public function dashboard()
    {
        return [
            'weeklyChart' => $this->weeklyRevenue(),
            ...$this->counter(),
            ...$this->recentSalesAndTopCustomer()
        ];
    }


    /**
     * @return array
     */
    private function weeklyRevenue(): array
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $reverseWeekDate = Carbon::now()->subDays(6)->format('Y-m-d');

        $sales = Sale::query()
            ->select(DB::raw("DATE(invoice_date) as date"), 'id')
            ->whereDate('invoice_date', '>=', $reverseWeekDate)
            ->whereDate('invoice_date', '<=', $currentDate)
            ->withCount(['saleProducts as total_profit' => function (Builder $builder) {
                $builder->select(DB::raw('sum((sale_price * quantity) - (unit_price * quantity))'));
            }])
            ->get()
            ->groupBy('date')
            ->toArray();

        $periods = CarbonPeriod::create($reverseWeekDate, $currentDate);

        $labels = [];
        $profitDailyTotal = [];
        foreach ($periods as $period) {
            $date = $period->format('Y-m-d');
            $labels[] = $period->format('d M');
            $dailyProfitSum = 0;
            if (isset($sales[$date])) {
                foreach ($sales[$date] ?? [] as $dailySale) {
                    $dailyProfitSum += $dailySale['total_profit'];
                }
            }
            $profitDailyTotal[] = $dailyProfitSum;
        }
        $max = max($profitDailyTotal);
        return [
            'labels' => $labels,
            'weeklyRevenue' => $profitDailyTotal,
            'max' => $max
        ];
    }

    /**
     * @return array
     */
    private function counter(): array
    {
        $todaySalesQuery = $this->sale->newQuery()
            ->select(['id', 'grand_total'])
            ->whereDate('invoice_date', now()->format('Y-m-d'));

        $todaySalesAmount = $todaySalesQuery->sum('grand_total');

        $todayEarningAmount = $todaySalesQuery
            ->withCount(['saleProducts as total_earning' => function (Builder $builder) {
                $builder->select(DB::raw('sum((sale_price * quantity) - (unit_price * quantity))'));
            }])
            ->get()
            ->sum('total_earning');

        $totalSales = $this->sale->newQuery()->sum('grand_total');
        $totalPurchase = $this->purchase->newQuery()->sum('total');

        return [
            'todaySales' => round($todaySalesAmount, 2),
            'todayEarning' => round($todayEarningAmount, 2),
            'totalSales' => round($totalSales, 2),
            'totalPurchase' => round($totalPurchase, 2),
        ];
    }

    /**
     * @return array
     */
    private function recentSalesAndTopCustomer(): array
    {
        return [
            'recent_sales' => $this->sale
                ->newQuery()
                ->with(['createdBy:id,name,role_id', 'createdBy.role:id,name', 'customer:id,name,phone_number'])
                ->orderByDesc('id')
                ->take(10)
                ->get([
                    "id", "invoice_number", "invoice_date", "customer_id",
                    "total_unit_qty", "subtotal", "other_cost", "discount",
                    "grand_total", "status", "payment_status", "total_paid",
                    "created_by",
                ]),
            'customers' => $this->customer
                ->newQuery()
                ->join('sales', 'sales.customer_id', '=', 'customers.id')
                ->select('customers.id', 'customers.name', 'customers.phone_number', DB::raw('sum(grand_total) as totalGrandTotal'))
                ->orderByDesc('totalGrandTotal')
                ->groupBy('customers.id', 'customers.name', 'customers.phone_number')
                ->take(10)
                ->get()
        ];
    }
}

<?php

namespace App\Services;

use App\Models\People\Customer;
use App\Models\People\Supplier;
use App\Models\Product\Category;
use App\Models\Product\Company;
use App\Models\Product\Product;
use App\Models\Purchase\Purchase;
use App\Models\Sale\Sale;
use App\Models\Stock\Stock;
use App\Models\trait\FileHandler;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardServices extends BaseServices
{
    use FileHandler;

    /**
     * @param $request
     * @return $this
     */
    public function validateProfileUpdate($request): static
    {
        $request->validate([
            "name" => "required|string",
            "email" => "required|email|unique:users,email,".auth()->id(),
            "phone_number" => "required|numeric|unique:users,phone_number,".auth()->id(),
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
            'weeklyChart' => $this->weeklySalesPurchase()
        ];
    }

    /**
     * @return array[]
     */
    private function weeklySalesPurchase(): array
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $reverseWeekDate = Carbon::now()->subDays(6)->format('Y-m-d');

        $sales = Sale::query()
            ->select(DB::raw("DATE(invoice_date) as date"), 'grand_total')
            ->whereDate('invoice_date', '>=', $reverseWeekDate)
            ->whereDate('invoice_date', '<=', $currentDate)
            ->get()
            ->groupBy('date')
            ->toArray();

        $purchases = Purchase::query()
            ->select('date', 'total')
            ->whereDate('date', '>=', $reverseWeekDate)
            ->whereDate('date', '<=', $currentDate)
            ->get()
            ->groupBy('date')
            ->toArray();


        $periods = CarbonPeriod::create($reverseWeekDate, $currentDate);

        $labels = [];
        $salesDailyTotal = [];
        $purchaseDailyTotal = [];
        foreach ($periods as $period) {
            $date = $period->format('Y-m-d');
            $labels[] = $period->format('d M');
            $dailySaleSum = 0;
            $dailyPurchaseSum = 0;
            if (isset($sales[$date])) {
                foreach ($sales[$date] ?? [] as $dailySale) {
                    $dailySaleSum += $dailySale['grand_total'];
                }
            }
            $salesDailyTotal[] = $dailySaleSum;
            if (isset($purchases[$date])) {
                foreach ($purchases[$date] ?? [] as $dailyPurchase) {
                    $dailyPurchaseSum += $dailyPurchase['total'];
                }
            }
            $purchaseDailyTotal[] = $dailyPurchaseSum;
        }

        $saleMax = max($salesDailyTotal);
        $purchaseMax = max($purchaseDailyTotal);
        $max = $saleMax > $purchaseMax ? $saleMax : $purchaseMax;

        return [
            'labels' => $labels,
            'weeklySale' => $salesDailyTotal,
            'weeklyPurchase' => $purchaseDailyTotal,
            'max' => $max
        ];
    }
}

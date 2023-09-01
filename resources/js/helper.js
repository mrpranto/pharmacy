import {notification} from "ant-design-vue";

export const showSuccessMessage = (message, placement, sound) => {
    notification.open({
        type: 'success',
        message: 'Success',
        description: message,
        placement,
    });
    if (sound === 'on'){
        let audio = new Audio('/assets/sounds/success.mp3');
        audio.play();
    }
}
export const showErrorMessage = (message,  placement, sound) => {
    notification.open({
        type: 'error',
        message: 'Error',
        description: message,
        placement,
    });
    if (sound === 'on') {
        let audio = new Audio('/assets/sounds/error.mp3');
        audio.play();
    }
}

export const showCurrency = (amount) => {
    const currency_symbol = window._general_setting?.currency_symbol;
    const currency_symbol_position = window._general_setting?.currency_symbol_position;

    if (currency_symbol_position === 'before_amount'){
       return currency_symbol + amount;
    }else if (currency_symbol_position === 'before_with_space_amount'){
        return currency_symbol +' '+ amount;
    }else if (currency_symbol_position === 'after_amount'){
        return amount + currency_symbol;
    }else if (currency_symbol_position === 'after_with_space_amount'){
        return amount +' '+ currency_symbol;
    }
}

export default {showSuccessMessage, showErrorMessage};

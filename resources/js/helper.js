import {notification} from "ant-design-vue";
import moment from "moment";

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

export const date_format = (date) => {
    const format = window._general_setting?.date_format;

    if (format === 'F d, Y')
    {
       return  moment(date).format('MMMM Do YYYY')
    }
    else if (format === 'd-m-Y')
    {
        return  moment(date).format('DD-MM-YYYY')
    }
    else if (format === 'Y-m-d')
    {
        return  moment(date).format('YYYY-MM-DD')
    }
    else if (format === 'm-d-Y')
    {
        return  moment(date).format('MM-DD-YYYY')
    }
    else if (format === 'd/m/Y')
    {
        return  moment(date).format('DD/MM/YYYY')
    }
    else if (format === ' Y/m/d')
    {
        return  moment(date).format('YYYY/MM/DD')
    }
    else if (format === 'm/d/Y')
    {
        return  moment(date).format('MM/DD/YYYY')
    }
    else if (format === 'd.m.Y')
    {
        return  moment(date).format('DD.MM.YYYY')
    }
    else if (format === 'Y.m.d')
    {
        return  moment(date).format('YYYY.MM.DD')
    }
    else if (format === 'm.d.Y')
    {
        return  moment(date).format('MM.DD.YYYY')
    }
}
export const time_format = (date_time) => {
    const format = window._general_setting?.time_format;

    if (format === 'H:i:s A')
    {
       return  moment(date_time).format('HH:mm:ss A')
    }
    else {
        return  moment(date_time).format('hh:mm:ss A')
    }
}


export default {showSuccessMessage, showErrorMessage};

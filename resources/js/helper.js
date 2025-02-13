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

export const showCurrency = (amnt) => {
    const currency_symbol = window._general_setting?.currency_symbol;
    const currency_symbol_position = window._general_setting?.currency_symbol_position;
    let amount = parseFloat(amnt)

    if (currency_symbol_position === 'before_amount'){
       return currency_symbol + amount.toLocaleString();
    }else if (currency_symbol_position === 'before_with_space_amount'){
        return currency_symbol +' '+ amount.toLocaleString();
    }else if (currency_symbol_position === 'after_amount'){
        return amount.toLocaleString() + currency_symbol;
    }else if (currency_symbol_position === 'after_with_space_amount'){
        return amount.toLocaleString() +' '+ currency_symbol;
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

export const attributeCombine = (attributes) => {
    // Convert attributes object to array of entries
    const attributeEntries = Object.entries(attributes);

    // Use reduce to combine attribute values
    const combinations = attributeEntries.reduce((acc, [key, values]) => {
        // Map each value to form combinations
        const mappedValues = values.map(value => ({
            [key]: value.value,
            "product_id": value.product_id,
            "has_variant": true
        }));

        // Generate combinations
        const newCombinations = acc.flatMap(combination =>
            mappedValues.map(mappedValue => Object.assign({}, combination, mappedValue))
        );

        return newCombinations;
    }, [{}]); // Start with an array containing an empty object

    return combinations;
}


export default {showSuccessMessage, showErrorMessage};

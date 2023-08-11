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

export default {showSuccessMessage, showErrorMessage};

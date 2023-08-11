import {notification} from "ant-design-vue";

export const showSuccessMessage = (message) => {
    notification['success']({
        message: 'Success',
        description: message,
    });
    let audio = new Audio('/assets/sounds/success.mp3');
    audio.play();
}
export const showErrorMessage = (message) => {
    notification['error']({
        message: 'Error',
        description: message,
    });
    let audio = new Audio('/assets/sounds/error.mp3');
    audio.play();
}

export default {showSuccessMessage, showErrorMessage};

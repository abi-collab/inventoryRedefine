import Noty from 'noty';

class Notification {
    success() {
        new Noty({
            type: 'success',
            layout: 'topRight',
            text: 'Successfully Done',
            timeout: 2000,
        }).show();
    }

    cart_success() {
        new Noty({
            type: 'success',
            layout: 'topRight',
            text: 'Successfully Added',
            timeout: 1000,
        }).show();
    }

    image_validation() {
        new Noty({
            type: 'error',
            layout: 'topRight',
            text: 'Upload image less than 1MB',
            timeout: 2000,
        }).show();
    }

    alert(text = 'Something went wrong') {
        new Noty({
            type: 'alert',
            layout: 'topRight',
            text,
            timeout: 2000,
        }).show();
    }

    error(text = 'Error') {
        new Noty({
            type: 'error',
            layout: 'topRight',
            text,
            timeout: 2000,
        }).show();
    }

    warning(text = 'Warning') {
        new Noty({
            type: 'warning',
            layout: 'topRight',
            text,
            timeout: 2000,
        }).show();
    }
}

export default new Notification();

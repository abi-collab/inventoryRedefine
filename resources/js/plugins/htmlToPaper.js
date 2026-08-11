export default {
    install(app) {
        app.config.globalProperties.$htmlToPaper = (elId) => {
            const element = document.getElementById(elId);
            if (!element) {
                return Promise.reject(new Error(`Element #${elId} not found`));
            }

            const win = window.open('', '_blank');
            if (!win) {
                return Promise.reject(new Error('Popup blocked'));
            }

            win.document.write(`<!doctype html><html><head><title>Print</title></head><body>${element.innerHTML}</body></html>`);
            win.document.close();
            win.focus();
            win.print();
            win.close();
            return Promise.resolve();
        };
    },
};

import * as Ajax from "WoltLabSuite/Core/Ajax";
import * as UiNotification from "WoltLabSuite/Core/Ui/Notification";
import * as Language from "WoltLabSuite/Core/Language";

export function addEventListener() {
    elById('jsKasMailResetListButton').addEventListener('click', (event: Event) => this._click(event));
}
export function _click(event: Event): void {
    event.preventDefault();

    Ajax.api({
        _ajaxSetup: () => {
            return {
                data: {
                    actionName: "execute",
                    className: "wcf\\action\\KasMailListResetAction"
                }
            };
        },
        _ajaxSuccess: () => {
            UiNotification.show(Language.get('wcf.global.success'), () => {
                window.location.reload();
            });
        }
    });
}

import * as Ajax from "WoltLabSuite/Core/Ajax";
import * as UiNotification from "WoltLabSuite/Core/Ui/Notification";
import * as Language from "WoltLabSuite/Core/Language";

export class KasMailResetList {
    constructor(buttonId: string) {
        const button = document.getElementById(buttonId);
        button?.addEventListener("click", (event) => void this.click(event));
    }
  
    async click(event: MouseEvent): Promise<void> {
        event.preventDefault();
  
        const button = event.currentTarget as HTMLElement;
        if (button.classList.contains("disabled")) {
            return;
        }
        button.classList.add("disabled");
  
        try {
            await Ajax.dboAction("execute", "wcf\\action\\KasMailListResetAction")
              .dispatch();
        } finally {
            button.classList.remove("disabled");
            UiNotification.show(Language.get('wcf.global.success'), () => {
                window.location.reload();
            });
        }
    }
}
  
export default KasMailResetList;

/*
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
*/

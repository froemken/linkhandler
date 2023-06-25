/*
 * This file is part of the package stefanfroemken/linkhandler.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

import LinkBrowser from "@typo3/backend/link-browser.js";
import RegularEvent from "@typo3/core/event/regular-event.js";

class MailLinkHandler {
  constructor() {
    new RegularEvent('submit', ((event, element) => {
      event.preventDefault();
      const urlSearchParams = new URLSearchParams;
      for (const formInputName of ["vendor", "package", "info"]) {
        const formInputElement = element.querySelector('[name="' + formInputName + '"]');
        formInputElement?.value.length && urlSearchParams.set(formInputName, encodeURIComponent(formInputElement.value))
      }
      let output = "t3://packagist";
      [...urlSearchParams].length > 0 && (output += "?" + urlSearchParams.toString());
      LinkBrowser.finalizeFunction(output);
    })).delegateTo(document, "#lhPackagistForm")
  }
}

export default new MailLinkHandler;

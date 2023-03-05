(function () {

  'use strict';

/* Main function, listens for a click event,
calls all the other functions upon element click */
Drupal.behaviors.copyButtonElements = {
  attach: function (context) {
    let btnCopy = document.querySelector('a.btnCopy.social-sharing-buttons__button');
    btnCopy.onclick = () => {
      // Checks if page is using HTTPS
      if (window.isSecureContext) {
        // Calls the secureCopyToClipboard function
        Drupal.secureCopyToClipboard(window.location.href);
      } else {
        // If site is not using HTTPS then use the fallback function
        Drupal.unsecureCopyToClipboard(window.location.href);
      }
    };
  }
};

// For HTTPS sites this is the function to copy current url to clipboard
Drupal.secureCopyToClipboard = function (valueToBeCopiedToClipboard) {
  // Here we use the clipboardAPI to copy to clipboard
  navigator.clipboard.writeText(valueToBeCopiedToClipboard)
    .then(() => {
      // Calls the function that pops up the message
      Drupal.showPopUpMessage();
    },function (err) {
      console.error('Error copying current URL to clipboard: ', err);
    });
};

// For non-HTTPS sites this will be the fallback function
Drupal.unsecureCopyToClipboard = function (valueToBeCopiedToClipboard) {
  const inputElem = document.createElement("input");
  inputElem.value = valueToBeCopiedToClipboard;
  // Append the element to the body
  document.body.append(inputElem);
  // Selects the element
  inputElem.select();
  try {
    /* This section copies the current selection to clipboard using 'execCommand',
    which is in the process of being deprecated, however its 'copy' command is still
    fully supported by major browsers. To learn more please follow the link below:
    https://developer.mozilla.org/en-US/docs/Web/API/Document/execCommand */
    document.execCommand('copy');
    this.showPopUpMessage();
  } catch (err) {
    // If unable to copy to clipboard, raise error
    console.error('Unable to copy to clipboard', err);
  }
  // We remove the appended input element
  document.body.removeChild(inputElem);
};

// Shows a popup if the current url was successfully copied.
Drupal.showPopUpMessage = function () {
  let elemPopUpShow = '.social-sharing-buttons__popup';
  // Adds 'visible' to class
  document.querySelector(elemPopUpShow).classList.add('visible');
  // Removes 'visible' from class after a certain time
  setTimeout(() => {
    document.querySelector(elemPopUpShow).classList.remove('visible');
  }, 4000);
};

})(Drupal);

jQuery(document).ready(function ($) {
  const popupText = welcomePopupSettings.text;
  const popupImage = welcomePopupSettings.image;
  const popupImageWidth = welcomePopupSettings.imageWidth;
  const displayOnce = welcomePopupSettings.displayOnce;

  // Eğer popup bir defa gösterilecekse ve cookie varsa çık
  if (
    displayOnce === "1" &&
    document.cookie.includes("welcome_popup_seen=true")
  ) {
    return;
  }

  const popupHtml = `
        <div id="welcome-popup" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border: 1px solid #ddd; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); z-index: 9999; text-align: center;">
            <img src="${popupImage}" alt="Popup Image" style="width: ${popupImageWidth}px; height: auto; margin-bottom: 20px;">
            <p>${popupText}</p>
            <button id="popup-close" style="margin-top: 10px; padding: 10px 20px; background: #f44336; color: white; border: none; border-radius: 4px; cursor: pointer;">Close</button>
        </div>
        <div id="popup-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9998;"></div>
    `;

  $("body").append(popupHtml);

  // Popup kapatıldığında cookie ayarla
  $("#popup-close, #popup-overlay").on("click", function () {
    $("#welcome-popup, #popup-overlay").remove();
    if (displayOnce === "1") {
      document.cookie = "welcome_popup_seen=true; path=/; max-age=86400"; // 1 gün boyunca sakla
    }
  });
});

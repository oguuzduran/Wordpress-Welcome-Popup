jQuery(document).ready(function ($) {
  let mediaUploader;

  $("#upload_image_button").on("click", function (e) {
    e.preventDefault();

    // Media Uploader'ı oluştur veya mevcut olanı kullan
    if (mediaUploader) {
      mediaUploader.open();
      return;
    }

    mediaUploader = wp.media({
      title: "Select Popup Image",
      button: {
        text: "Use This Image",
      },
      multiple: false,
    });

    // Resim seçildiğinde işlemi gerçekleştir
    mediaUploader.on("select", function () {
      const attachment = mediaUploader
        .state()
        .get("selection")
        .first()
        .toJSON();
      $("#welcome_popup_image").val(attachment.url);
      $("#image_preview").html(
        `<img src="${attachment.url}" alt="Popup Image">`
      );
    });

    mediaUploader.open();
  });
});

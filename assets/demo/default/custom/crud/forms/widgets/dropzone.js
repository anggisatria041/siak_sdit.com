var DropzoneDemo = {
    init: function () {
        Dropzone.options.mDropzoneOne = {
            paramName: "file",
            maxFiles: 1,
            maxFilesize: 5,
            addRemoveLinks: !0,
            accept: function (e, o) {
                "justinbieber.jpg" == e.name ? o("Naha, you don't.") : o()
            }
        }, Dropzone.options.mDropzoneTwo = {
            paramName: "file",
            maxFiles: 10,
            maxFilesize: 10,
            addRemoveLinks: !0,
            accept: function (e, o) {
                "justinbieber.jpg" == e.name ? o("Naha, you don't.") : o()
            }
        }, Dropzone.options.mDropzoneThree = {
            paramName: "file",
            maxFiles: 10,
            maxFilesize: 10,
            addRemoveLinks: !0,
            acceptedFiles: "image/*,application/pdf,.psd",
            accept: function (e, o) {
                "justinbieber.jpg" == e.name ? o("Naha, you don't.") : o()
            }
        }, Dropzone.options.mDropzoneFour = {
            paramName: "file",
            maxFiles: 1,
            maxFilesize: 5,
            addRemoveLinks: !0,
            acceptedFiles: ".xls, .xlsx, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            beforeSend: function () {
                mApp.block(".modal-content", {//block modal
                    overlayColor: "#000000",
                    type: "loader",
                    state: "primary",
                    message: "Please wait..."
                });
            },
            success: function (file, response) {
                var data = JSON.parse(response);
                if (data.status == 'success')
                {
                    csrfName = data.csrf.csrfName;
                    csrfHash = data.csrf.csrfHash;
                    $('.modal').modal('hide');
                    this.removeAllFiles();
                    swal("Berhasil..", "Data anda berhasil disimpan", "success");
                    reload_table(data.table);
                    mApp.unblock(".modal-content");
                } else
                {
                    csrfName = data.csrf.csrfName;
                    csrfHash = data.csrf.csrfHash;
                    $('.modal').modal('hide');
                    this.removeAllFiles();
                    swal({
                        text: data.message,
                        type: "warning",
                        closeOnConfirm: true
                    });
                    if (data.message == "Terdapat kesalahan pada file") {
                        window.open(data.file, '_blank');
                        mApp.unblock(".modal-content");
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                mApp.unblock(".modal-content");
                swal("Oops", "Data gagal disimpan !", "error");
                $('#btnSave').text('save'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable 
            }
        }
    }
};
DropzoneDemo.init();
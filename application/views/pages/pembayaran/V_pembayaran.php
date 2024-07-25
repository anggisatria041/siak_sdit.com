<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">
                    Pembayaran
                </h3>
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item m-nav__item--home">
                        <a href="" class="m-nav__link m-nav__link--icon">
                            <i class="m-nav__link-icon la la-home"></i>
                        </a>
                    </li>
                    <li class="m-nav__separator">
                        -
                    </li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link">
                            <span class="m-nav__link-text">
                                Pembayaran
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- END: Subheader -->

    <!-- END: Subheader -->
    <div class="m-content">
        <div class="row">
            <div class="col-md-12">
                <div class="m-portlet m-portlet--head-solid-bg m-portlet--bordered m-portlet--brand">
                    <div class="m-portlet__head ">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    Manage Data Pembayaran<p id="hax"></p>
                                </h3>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <button type="button" class="btn btn-info btn-md" onclick="add_ajax()">
                                <i class="la la-plus"></i> Tambah Pembayaran
                            </button>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <?php if ($this->session->flashdata('alert1')) { ?>
                            <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-<?= $this->session->flashdata('alert1') ?> alert-dismissible fade show"
                                role="alert">
                                <div class="m-alert__icon">
                                    <i class="flaticon-exclamation-1"></i>
                                    <span></span>
                                </div>
                                <div class="m-alert__text">
                                    <?= $this->session->flashdata('alert2') ?>
                                </div>
                                <div class="m-alert__close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </div>
                        <?php } ?>
                        <!--begin: Datatable -->
                        <div class="m_datatable" id="tableManagepembayaran"></div>
                        <!--end: Datatable -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="m_modal_6" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header m--bg-brand">
                    <h5 class="modal-title m--font-light" id="exampleModalLongTitle">
                        Tambah pembayaran
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <form class="m-form m-form--fit m-form--label-align-right" action="" method="POST" id="formAdd"
                    enctype="multipart/form-data">

                    <div class="modal-body">
                        <div class="m-form__content">
                            <div class="m-alert m-alert--icon alert alert-danger" role="alert" id="m_form_1_msg">
                                <div class="m-alert__icon">
                                    <i class="la la-warning"></i>
                                </div>
                                <div class="m-alert__text">
                                    Upss .. ! Periksa kembali data yang anda inputkan, pastikan seluruh kolom required
                                    terisi.
                                </div>
                                <div class="m-alert__close">
                                    <button type="button" class="close" data-close="alert" aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="pembayaran_id" value="">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value=""
                            style="display: none">
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                NIS <font class="m--font-danger">*</font>
                            </label>
                            <div class="col-md-6">
                                <select name="nis" required class="form-control m-input m-select2">
                                    <option value="">Pilih NIS</option>
                                    <?php foreach ($siswa as $row) { ?>
                                        <option value="<?= $row->nis ?>"><?= $row->nis ?>-<?= $row->nama ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Nama Tajaran <font class="m--font-danger">*</font>
                            </label>
                            <div class="col-md-6">
                                <select name="nama_tajaran" required class="form-control m-input m-select2">
                                    <option value="">Pilih Tahun Ajaran</option>
                                    <?php foreach ($tajaran as $row) { ?>
                                        <?php if ($row->semester == '1') { ?>
                                            <option value="<?= $row->tajaran_id ?>"><?= $row->nama_tajaran ?> - Ganjil </option>
                                        <?php } else { ?>
                                            <option value="<?= $row->tajaran_id ?>"><?= $row->nama_tajaran ?> - Genap</option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Nominal <font class="m--font-danger">*</font>
                            </label>
                            <div class="col-md-6">
                                <input type="text" name="nominal" required class="form-control m-input"
                                    placeholder="Nominal" />
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Tanggal Pembayaran (optional)
                            </label>
                            <div class="col-md-6">
                                <input type="date" name="tanggal_pembayaran" required class="form-control m-input"
                                    placeholder="Tanggal Pembayaran" />
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Status Pembayaran <font class="m--font-danger">*</font>
                            </label>
                            <div class="col-md-6">
                                <select name="status_pembayaran" required class="form-control m-input m-select2">
                                    <option value="">Pilih Status Pembayaran</option>
                                    <option value="lunas">Lunas</option>
                                    <option value="belum lunas">Belum Lunas</option>
                                    <option value="menunggu verifikasi">Menunggu Verifikasi</option>
                                    <option value="ditolak">Ditolak</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">

                            </label>
                            <div class="col-md-6">
                                <img id="bukti_pembayaran" width="100" src="" alt="Bukti Pembayaran">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Bukti Pembayaran(optional)
                            </label>
                            <div class="col-md-6">
                                <input type="file" name="bukti_pembayaran" required class="form-control m-input"
                                    placeholder="Bukti Pembayaran"
                                    onchange="document.getElementById('bukti_pembayaran').src = window.URL.createObjectURL(this.files[0])" />
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Catatan(optional)
                            </label>
                            <div class="col-md-6">
                                <textarea name="catatan" class="form-control m-input" placeholder="Catatan"></textarea>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-warning" data-dismiss="modal">
                            Batal
                        </a>
                        <a href="#" onclick="save()" id="btnSaveAjax" class="btn btn-accent">
                            Simpan
                        </a>

                    </div>
                </form>
                <!--end::Form-->
            </div>
        </div>
    </div>

    <?php foreach ($pembayaran as $row) { ?>
        <div class="modal fade" id="modalImage-<?php echo $row->pembayaran_id ?>" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header m--bg-brand">
                        <h5 class="modal-title m--font-light" id="exampleModalLongTitle">
                            Lihat Bukti Pembayaran
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">
                                &times;
                            </span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img src="<?php echo base_url() . 'assets/upload/bukti_pembayaran/' . $row->bukti_pembayaran ?>"
                            alt="Bukti Pembayaran" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="modal fade" id="modal_varifikasi" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header m--bg-brand">
                    <h5 class="modal-title m--font-light" id="exampleModalLongTitle">
                        Verifikasi Pembayaran
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <form class="m-form m-form--fit m-form--label-align-right" action="" method="POST" id="formverifikasi"
                    enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="pembayaran_id" value="">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value=""
                            style="display: none">
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Status Pembayaran <font class="m--font-danger">*</font>
                            </label>
                            <div class="col-md-6">
                                <select name="status_pembayaran" required class="form-control m-input m-select2">
                                    <option value="">Pilih Status Pembayaran</option>
                                    <option value="lunas">Lunas</option>
                                    <option value="belum lunas">Belum Lunas</option>
                                    <option value="menunggu verifikasi">Menunggu Verifikasi</option>
                                    <option value="ditolak">Ditolak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-warning" data-dismiss="modal">
                            Batal
                        </a>
                        <a href="#" onclick="saveverifikasi()" id="btnSaveAjax" class="btn btn-accent">
                            Simpan
                        </a>

                    </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_uploadbukti" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header m--bg-brand">
                    <h5 class="modal-title m--font-light" id="exampleModalLongTitle">
                        Verifikasi Pembayaran
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <form class="m-form m-form--fit m-form--label-align-right" action="" method="POST" id="formverifikasi"
                    enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="pembayaran_id" value="">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value=""
                            style="display: none">
                            <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">

                            </label>
                            <div class="col-md-6">
                                <img id="bukti_pembayaran" width="100" src="" alt="Bukti Pembayaran">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Bukti Pembayaran(optional)
                            </label>
                            <div class="col-md-6">
                                <input type="file" name="file_bukti_pembayaran" required class="form-control m-input"
                                    placeholder="Bukti Pembayaran"
                                    onchange="document.getElementById('bukti_pembayaran').src = window.URL.createObjectURL(this.files[0])" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-warning" data-dismiss="modal">
                            Batal
                        </a>
                        <a href="#" onclick="saveverifikasi()" id="btnSaveAjax" class="btn btn-accent">
                            Simpan
                        </a>

                    </div>
            </div>
        </div>
    </div>

    <!-- End Modal -->
</div>
<script type="text/javascript">
    var method = '';
    var csrfName = '<?= $this->security->get_csrf_token_name(); ?>',
        csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

    function resetForm() {
        $('#m_form_1_msg').hide();
        $('#formAdd')[0].reset();
        $('.m-select2').select2({
            width: '100%'
        });
    }
    function renew(x) {
    }

    function add_ajax() {
        method = 'add';
        resetForm();
        $('#exampleModalLongTitle').html("Tambah pembayaran");
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#m_form_1_msg').hide();
        $('#m_modal_6').modal('show');
        $('#btnSaveAjax').show();
    }
    function img(src) {
        method = 'add';
        resetForm();
        $('#exampleModalLongTitle').html("Lihat Gambar Bukti Pembayaran");
        $('#modalImage-' + src).modal('show');
    }
    // function openModal(src) {
    //     $('#modalImage').attr('src', src);
    //     $('#modalImage').modal('show');
    //     $('#modalImage').css('width', '100%');
    //     $('#modalImage').attr('style', 'height: auto;');
    // }
    function uploadBuktiPembayaran(id) {
        method = 'uploadBuktiPembayaran';
        resetForm();
        $('#btnSaveAjax').show();
        $('#exampleModalLongTitle').html("Upload Bukti Pembayaran");

        $.ajax({
            url: "<?php echo base_url() . 'dir/C_pembayaran/verifikasi' ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                if (data.data == true) {

                    $('#formAdd')[0].reset();
                    $('[name="pembayaran_id"]').val(data.pembayaran_id);
                    $('[name="file_bukti_pembayaran"]').val(data.bukti_pembayaran);
                    $('.m-select2').select2({ width: '100%' });
                    $('#modal_uploadbukti').modal('show');

                } else if (data.data == false) {
                    swal("Oops", "Data gagal mengambil data!", "error");
                } else {
                    swal("Gagal", data.message, "warning");
                }
                mApp.unblockPage();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                mApp.unblockPage();
                alert('Error get data from ajax');
            }
        });
        $('#formAdd')[0].reset();
    }
    function edit(id) {

        method = 'edit';
        resetForm();
        $('#btnSaveAjax').show();
        $('#exampleModalLongTitle').html("Edit pembayaran");

        $.ajax({
            url: "<?php echo base_url() . 'dir/C_pembayaran/edit' ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                if (data.data == true) {
                    let lokasi = '<?= base_url() ?>assets/upload/bukti_pembayaran/' + data.bukti_pembayaran;
                    $('#formAdd')[0].reset();
                    $('[name="pembayaran_id"]').val(data.pembayaran_id);
                    $('#bukti_pembayaran').attr('src', lokasi);

                    $('[name="nis"]').val(data.nis);

                    $('[name="nama_siswa"]').val(data.nama_siswa);

                    $('[name="nama_tajaran"]').val(data.tajaran_id);

                    $('[name="nominal"]').val(data.nominal);
                    $('[name="tanggal_pembayaran"]').val(data.tanggal_pembayaran);

                    $('[name="status_pembayaran"]').val(data.status_pembayaran);

                    // $('[name="bukti_pembayaran"]').val(data.bukti_pembayaran);

                    $('[name="catatan"]').val(data.catatan);

                    $('[name="status"]').val(data.status);

                    $('.m-select2').select2({ width: '100%' });
                    $('#m_modal_6').modal('show');

                } else if (data.data == false) {
                    swal("Oops", "Data gagal mengambil data!", "error");
                } else {
                    swal("Gagal", data.message, "warning");
                }
                mApp.unblockPage();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                mApp.unblockPage();
                alert('Error get data from ajax');
            }
        });
        $('#formAdd')[0].reset();
    }
    function verifikasi(id) {

        method = 'verifikasi';
        resetForm();
        $('#btnSaveAjax').show();
        $('#exampleModalLongTitle').html("Verifikasi Pembayaran");

        $.ajax({
            url: "<?php echo base_url() . 'dir/C_pembayaran/verifikasi' ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                if (data.data == true) {

                    $('#formAdd')[0].reset();
                    $('[name="pembayaran_id"]').val(data.pembayaran_id);
                    $('[name="status_pembayaran"]').val(data.status_pembayaran);
                    $('.m-select2').select2({ width: '100%' });
                    $('#modal_varifikasi').modal('show');

                } else if (data.data == false) {
                    swal("Oops", "Data gagal mengambil data!", "error");
                } else {
                    swal("Gagal", data.message, "warning");
                }
                mApp.unblockPage();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                mApp.unblockPage();
                alert('Error get data from ajax');
            }
        });
        $('#formAdd')[0].reset();
    }



    function save() {

        var url;
        if (method == 'add') {
            url = "<?= base_url() . 'dir/C_pembayaran/add' ?>";
        } else if (method == 'verifikasi') {
            url = "<?= base_url() . 'dir/C_pembayaran/verifikasi' ?>";
        } else {
            url = "<?= base_url() . 'dir/C_pembayaran/update' ?>";
        }

        // ajax adding data to database
        if ($('[name="nis"]').val() == "" || $('[name="nama_tajaran"]').val() == "" || $('[name="nominal"]').val() == "" || $('[name="status_pembayaran"]').val() == "") {
            $('#m_form_1_msg').show();
            mApp.unblock(".modal-content");
        } else {
            $('[name="' + csrfName + '"]').val(csrfHash);
            $.ajax({
                url: url,
                type: "POST",
                data: new FormData($('#formAdd')[0]), //this is formData
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                dataType: "JSON",
                success: function (data) {
                    if (data.status == 'success') {
                        csrfName = data.csrf.csrfName;
                        csrfHash = data.csrf.csrfHash;
                        $('#m_modal_6').modal('hide');
                        swal("Berhasil..", "Data anda berhasil disimpan", "success");
                        reload_table('tableManagepembayaran');
                    } else {
                        csrfName = data.csrf.csrfName;
                        csrfHash = data.csrf.csrfHash;
                        swal({
                            text: data.message,
                            type: "warning",
                            closeOnConfirm: true
                        });

                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    swal("Oops", "Data gagal disimpan !", "error");
                    $('#btnSave').text('save'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable 
                }
            });
        }
    }

    function saveverifikasi() {

        var url = "<?= base_url() . 'dir/C_pembayaran/updateverifikasi' ?>";

        // ajax adding data to database
        if ($('[name="status_pembayaran"]').val() == "") {
            $('#m_form_1_msg').show();
            mApp.unblock(".modal-content");
        } else {
            $('[name="' + csrfName + '"]').val(csrfHash);
            $.ajax({
                url: url,
                type: "POST",
                data: new FormData($('#formverifikasi')[0]), //this is formData
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                dataType: "JSON",
                success: function (data) {
                    if (data.status == 'success') {
                        csrfName = data.csrf.csrfName;
                        csrfHash = data.csrf.csrfHash;
                        $('#modal_varifikasi').modal('hide');
                        swal("Berhasil..", "Data anda berhasil disimpan", "success");
                        reload_table('tableManagepembayaran');
                    } else {
                        csrfName = data.csrf.csrfName;
                        csrfHash = data.csrf.csrfHash;
                        swal({
                            text: data.message,
                            type: "warning",
                            closeOnConfirm: true
                        });

                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    swal("Oops", "Data gagal disimpan !", "error");
                    $('#btnSave').text('save'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable 
                }
            });
        }
    }

    function hapus(id) {
        swal({
            title: "Apakah anda yakin?",
            text: "Anda yakin ingin cancel data ini?",
            type: "warning",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "<span><i class='flaticon-interface-1'></i><span>Ya, Cancel!</span></span>",
            confirmButtonClass: "btn btn-danger m-btn m-btn--pill m-btn--icon",
            cancelButtonText: "<span><i class='flaticon-close'></i><span>Batal Cancel</span></span>",
            cancelButtonClass: "btn btn-metal m-btn m-btn--pill m-btn--icon"
        }).then(function (e) {
            if (e.value) {
                mApp.blockPage({ //block page
                    overlayColor: "#000000",
                    type: "loader",
                    state: "primary",
                    message: "Please wait..."
                });

                $.ajax({
                    url: "<?php echo base_url() . 'dir/C_pembayaran/delete' ?>/" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function (data) {
                        if (data.data == true) {
                            swal("Berhasil..", "Data berhasil dicancel", "success");
                            reload_table('tableManagepembayaran');
                        } else if (data.data == false) {
                            swal("Oops", "Data gagal dicancel!", "error");
                        } else {
                            swal("Gagal", data.message, "warning");
                        }
                        mApp.unblockPage();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        mApp.unblockPage();
                        swal("Oops", "Data gagal dicancel!", "error");
                    }
                })
            }
        });
    }
</script>
<?= isset($tableManagepembayaran) ? $tableManagepembayaran : '' ?>
<!-- end:: Body -->
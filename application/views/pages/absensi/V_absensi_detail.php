<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
    <script src="path/to/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="path/to/jquery.dataTables.min.css">
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">
                    Detail Absensi
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
                                Detail Absensi
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
                                    Detail Absensi<p id="hax"></p>
                                </h3>
                            </div>
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
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                <label for="bulan">Bulan</label>
                                <select class="form-control" id="bulan" name="bulan"
                                    onchange="changeBulan('<?= $id_kelas ?>', this.value)">
                                    <option value="">Pilih Bulan</option>
                                    <option value="1" <?= $bulan == '1' ? 'selected' : '' ?>>Januari</option>
                                    <option value="2" <?= $bulan == '2' ? 'selected' : '' ?>>Februari</option>
                                    <option value="3" <?= $bulan == '3' ? 'selected' : '' ?>>Maret</option>
                                    <option value="4" <?= $bulan == '4' ? 'selected' : '' ?>>April</option>
                                    <option value="5" <?= $bulan == '5' ? 'selected' : '' ?>>Mei</option>
                                    <option value="6" <?= $bulan == '6' ? 'selected' : '' ?>>Juni</option>
                                    <option value="7" <?= $bulan == '7' ? 'selected' : '' ?>>Juli</option>
                                    <option value="8" <?= $bulan == '8' ? 'selected' : '' ?>>Agustus</option>
                                    <option value="9" <?= $bulan == '9' ? 'selected' : '' ?>>September</option>
                                    <option value="10" <?= $bulan == '10' ? 'selected' : '' ?>>Oktober</option>
                                    <option value="11" <?= $bulan == '11' ? 'selected' : '' ?>>November</option>
                                    <option value="12" <?= $bulan == '12' ? 'selected' : '' ?>>Desember</option>
                                </select>
                        </div>
                        <!--begin: Datatable -->
                        <div class="m_datatable" id="tableManageDetail"></div>
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
                    <!-- <h5 class="modal-title m--font-light" id="exampleModalLongTitle">
                        Tambah Absensi
                    </h5> -->
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
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value=""
                            style="display: none">
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Tahun Ajaran <font class="m--font-danger">*</font>
                            </label>
                            <div class="col-md-6">
                                <input type="hidden" name="tajaran_id" required class="form-control m-input" />
                                <input type="hidden" name="nis" required class="form-control m-input" />
                                <input type="text" name="nama_tajaran" required class="form-control m-input" readonly
                                    placeholder="Tahun Ajaran" />
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Bulan <font class="m--font-danger">*</font>
                            </label>
                            <div class="col-md-6">
                                <input type="hidden" name="bulan" required class="form-control m-input" />
                                <input type="text" name="nama_bulan" required class="form-control m-input" readonly
                                    placeholder="Bulan" />
                            </div>
                        </div>

                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Nama Siswa <font class="m--font-danger">*</font>
                            </label>
                            <div class="col-md-6">
                                <input type="text" name="nama_siswa" required class="form-control m-input"
                                    placeholder="Nama Siswa" readonly />
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Tanggal
                            </label>
                            <div class="col-md-6">
                                <label class="col-form-label col-md-3" style="text-align:left">
                                    Kehadiran
                            </div>
                        </div>
                        <div class="form-group m-form__group row">

                            <div class="col-md-12">

                                <div id="siswa-list"></div>
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
    <!-- End Modal -->
</div>
<script type="text/javascript">
    var method = '';
    var csrfName = '<?= $this->security->get_csrf_token_name(); ?>',
        csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

    function resetForm() {
        $('#m_form_1_msg').hide();
        $('#formAdd')[0].reset();
        $('[name="kelas_id"] :selected').removeAttr('selected');
        $('.m-select2').select2({
            width: '100%'
        });
    }
    function renew(x) {
    }

    function add_ajax() {
        method = 'add';
        resetForm();
        $('#exampleModalLongTitle').html("Tambah Absensi");
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#m_form_1_msg').hide();
        $('#m_modal_6').modal('show');
        $('#btnSaveAjax').show();
    }
    function changeBulan(id, value) {
        alert('berhasil');
        window.location.href = "<?= base_url() ?>dir/C_Absensi_detail/index/" + id + "/" + value;
    }

    function edit(id) {

        method = 'edit';
        resetForm();
        $('#btnSaveAjax').show();
        $('#exampleModalLongTitle').html("Input Absensi");

        $.ajax({
            url: "<?php echo base_url() . 'dir/C_absensi_detail/edit' ?>/" + id,
            type: "GET",
            data: {
                bulan: <?= $bulan ?>,
            },
            dataType: "JSON",
            success: function (data) {
                if (data.data == true) {
                    $('#formAdd')[0].reset();
                    $('[name="tajaran_id"]').val(data.tajaran_id);
                    $('[name="nis"]').val(data.nis);
                    $('[name="nama_tajaran"]').val(data.nama_tajaran);
                    $('[name="nama_siswa"]').val(data.nama_siswa);
                    $('[name="nama_bulan"]').val(data.nama_bulan);
                    $('[name="bulan"]').val(data.bulan);
                    $('#siswa-list').empty();
                    for (let i = 1; i <= 31; i++) {
                        let kehadiran = data['kehadiran_' + i];
                        let selectedHadir = kehadiran === 'hadir' ? 'checked' : '';
                        let selectedIzin = kehadiran === 'izin' ? 'checked' : '';
                        let selectedSakit = kehadiran === 'sakit' ? 'checked' : '';
                        let selectedAlfa = kehadiran === 'alfa' ? 'checked' : '';
                        if (!selectedHadir && !selectedIzin && !selectedSakit && !selectedAlfa) {
                        } else {
                            $('#siswa-list').append('<div class="row m-input"><div class="col-md-3">' + i + '</div><div class="col-md-9"><div class="m-radio-inline"><label class="m-radio"><input type="radio" name="siswa_hadir[' + i + ']" value="hadir" required ' + selectedHadir + '>Hadir<span></span></label><label class="m-radio"><input type="radio" name="siswa_hadir[' + i + ']" value="izin" required ' + selectedIzin + '>Izin<span></span></label><label class="m-radio"><input type="radio" name="siswa_hadir[' + i + ']" value="sakit" required ' + selectedSakit + '>Sakit<span></span></label><label class="m-radio"><input type="radio" name="siswa_hadir[' + i + ']" value="alfa" required ' + selectedAlfa + '>Alfa<span></span></label></div></div></div>');
                        }

                    }

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

    function save() {

        var url;
        if (method == 'add') {
            url = "<?= base_url() . 'dir/C_absensi/add' ?>";
        } else {
            url = "<?= base_url() . 'dir/C_absensi_detail/update' ?>";
        }

        // ajax adding data to database
        if ($('[name="bulan"]').val() == "" || $('[name="nama_siswa"]').val() == "") {
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
                        reload_table('tableManageDetail');
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

</script>

<?= isset($tableManageDetail) ? $tableManageDetail : '' ?>
<!-- end:: Body -->
<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">
                   Orang Tua
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
                                Orang Tua
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
                                    Manage Data Orang Tua<p id="hax"></p>
                                </h3>
                            </div>
                        </div>
                       <div class="m-portlet__head-tools">
                            <button type="button" class="btn btn-info btn-md" onclick="add_ajax()">
                                <i class="la la-plus"></i> Tambah Orang Tua
                            </button>
                        </div>
                    </div>
                     <div class="m-portlet__body">                                
                        <?php if ($this->session->flashdata('alert1')) { ?>
                            <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-<?= $this->session->flashdata('alert1') ?> alert-dismissible fade show" role="alert">
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
                        <div class="m_datatable" id="tableManageOrangTua"></div>
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
                        Tambah Orang Tua
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <form class="m-form m-form--fit m-form--label-align-right" action="" method="POST" id="formAdd" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="m-form__content">
                            <div class="m-alert m-alert--icon alert alert-danger" role="alert" id="m_form_1_msg">
                                <div class="m-alert__icon">
                                    <i class="la la-warning"></i>
                                </div>
                                <div class="m-alert__text">
                                    Upss .. ! Periksa kembali data yang anda inputkan, pastikan seluruh kolom required terisi.
                                </div>
                                <div class="m-alert__close">
                                    <button type="button" class="close" data-close="alert" aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id_orang_tua" value="">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="" style="display: none">
                        
                        <!-- Data Ayah -->
                        <h4>Data Ayah</h4>
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Nama Ayah <font class="m--font-danger">*</font>
                            </label>
                            <div class="col-md-6">
                                <input type="text" name="nama_ayah" required class="form-control m-input" placeholder="Nama Ayah"/>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Tahun Lahir Ayah <font class="m--font-danger">*</font>
                            </label>
                            <div class="col-md-6">
                                <input type="date" name="tahun_lahir_ayah" required class="form-control m-input" placeholder="Tahun Lahir Ayah"/>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Pekerjaan Ayah <font class="m--font-danger">*</font>
                            </label>
                            <div class="col-md-6">
                                <input type="text" name="pekerjaan_ayah" required class="form-control m-input" placeholder="Pekerjaan Ayah"/>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Pendidikan Ayah <font class="m--font-danger">*</font>
                            </label>
                            <div class="col-md-6">
                                <input type="text" name="pendidikan_ayah" required class="form-control m-input" placeholder="Pendidikan Ayah"/>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Penghasilan Ayah <font class="m--font-danger">*</font>
                            </label>
                            <div class="col-md-6">
                                <input type="number" name="penghasilan_ayah" required class="form-control m-input" placeholder="Penghasilan Ayah"/>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Alamat Ayah <font class="m--font-danger">*</font>
                            </label>
                            <div class="col-md-6">
                                <textarea name="alamat_ayah" required class="form-control m-input" placeholder="Alamat Ayah"></textarea>
                            </div>
                        </div>

                        <!-- Data Ibu -->
                        <h4>Data Ibu</h4>
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Nama Ibu <font class="m--font-danger">*</font>
                            </label>
                            <div class="col-md-6">
                                <input type="text" name="nama_ibu" required class="form-control m-input" placeholder="Nama Ibu"/>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Tahun Lahir Ibu <font class="m--font-danger">*</font>
                            </label>
                            <div class="col-md-6">
                                <input type="date" name="tahun_lahir_ibu" required class="form-control m-input" placeholder="Tahun Lahir Ibu"/>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Pekerjaan Ibu <font class="m--font-danger">*</font>
                            </label>
                            <div class="col-md-6">
                                <input type="text" name="pekerjaan_ibu" required class="form-control m-input" placeholder="Pekerjaan Ibu"/>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Pendidikan Ibu <font class="m--font-danger">*</font>
                            </label>
                            <div class="col-md-6">
                                <input type="text" name="pendidikan_ibu" required class="form-control m-input" placeholder="Pendidikan Ibu"/>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Penghasilan Ibu <font class="m--font-danger">*</font>
                            </label>
                            <div class="col-md-6">
                                <input type="number" name="penghasilan_ibu" required class="form-control m-input" placeholder="Penghasilan Ibu"/>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <label class="col-form-label col-md-3" style="text-align:left">
                                Alamat Ibu <font class="m--font-danger">*</font>
                            </label>
                            <div class="col-md-6">
                                <textarea name="alamat_ibu" required class="form-control m-input" placeholder="Alamat Ibu"></textarea>
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
    }
    function renew(x) {
    }

    function add_ajax() {
        method = 'add';
        resetForm();
        $('#exampleModalLongTitle').html("Tambah Orang Tua");
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#m_form_1_msg').hide();
        $('#m_modal_6').modal('show');
        $('#btnSaveAjax').show();
    }

    function edit(id) {
        
        method = 'edit';
        resetForm();
        $('#btnSaveAjax').show();
        $('#exampleModalLongTitle').html("Edit Orang Tua");

        $.ajax({
            url: "<?php echo base_url() . 'dir/C_orang_tua/edit' ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                if (data.data == true) {
                    $('#formAdd')[0].reset();
                    $('[name="id_orang_tua"]').val(data.id_orang_tua);
                    // Data Ayah
                    $('[name="nama_ayah"]').val(data.nama_ayah);
                    $('[name="tahun_lahir_ayah"]').val(data.tahun_lahir_ayah);
                    $('[name="pekerjaan_ayah"]').val(data.pekerjaan_ayah);
                    $('[name="pendidikan_ayah"]').val(data.pendidikan_ayah);
                    $('[name="penghasilan_ayah"]').val(data.penghasilan_ayah);
                    $('[name="alamat_ayah"]').val(data.alamat_ayah);

                    // Data Ibu
                    $('[name="nama_ibu"]').val(data.nama_ibu);
                    $('[name="tahun_lahir_ibu"]').val(data.tahun_lahir_ibu);
                    $('[name="pekerjaan_ibu"]').val(data.pekerjaan_ibu);
                    $('[name="pendidikan_ibu"]').val(data.pendidikan_ibu);
                    $('[name="penghasilan_ibu"]').val(data.penghasilan_ibu);
                    $('[name="alamat_ibu"]').val(data.alamat_ibu);

                    $('.m-select2').select2({width : '100%'});
                    $('#m_modal_6').modal('show');

                } else if (data.data == false) {
                    swal("Oops", "Data gagal mengambil data!", "error");
                } else {
                    swal("Gagal", data.message, "warning");
                }
                mApp.unblockPage();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                mApp.unblockPage();
                alert('Error get data from ajax');
            }
        });
        $('#formAdd')[0].reset();
    }

    function save() {
       
        var url;
        if (method == 'add') {
            url = "<?= base_url() . 'dir/C_orang_tua/add' ?>";
        } else {
            url = "<?= base_url() . 'dir/C_orang_tua/update' ?>";
        }

        // ajax adding data to database
        if ($('[name="nama_ayah"]').val() == "" || $('[name="nama_ibu"]').val() == "") {
            $('#m_form_1_msg').show();
            mApp.unblock(".modal-content");
        } else {
            $('[name="' + csrfName + '"]').val(csrfHash);
            $.ajax({
                url: url,
                type: "POST",
                data: new FormData($('#formAdd')[0]), 
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                dataType: "JSON",
                success: function(data) {
                    if (data.status == 'success') {
                        csrfName = data.csrf.csrfName;
                        csrfHash = data.csrf.csrfHash;
                        $('#m_modal_6').modal('hide');
                        swal("Berhasil..", "Data anda berhasil disimpan", "success");
                        reload_table('tableManageOrangTua');
                    } else{
                        csrfName = data.csrf.csrfName;
                        csrfHash = data.csrf.csrfHash;
                        swal({
                            text: data.message,
                            type: "warning",
                            closeOnConfirm: true
                        });
                        
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    swal("Oops", "Data gagal disimpan !", "error");
                    $('#btnSave').text('save'); //change button text
                    $('#btnSave').attr('disabled',false); //set button enable 
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
        }).then(function(e) {
            if (e.value) {
                mApp.blockPage({ //block page
                    overlayColor: "#000000",
                    type: "loader",
                    state: "primary",
                    message: "Please wait..."
                });

                $.ajax({
                    url: "<?php echo base_url() . 'dir/C_orang_tua/delete' ?>/" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        if (data.data == true) {
                            swal("Berhasil..", "Data berhasil dicancel", "success");
                            reload_table('tableManageOrangTua');
                        } else if (data.data == false) {
                            swal("Oops", "Data gagal dicancel!", "error");
                        } else {
                            swal("Gagal", data.message, "warning");
                        }
                        mApp.unblockPage();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        mApp.unblockPage();
                        swal("Oops", "Data gagal dicancel!", "error");
                    }
                })
            }
        });
    }
</script>
<?= isset($tableManageOrangTua) ? $tableManageOrangTua : '' ?>
<!-- end:: Body -->

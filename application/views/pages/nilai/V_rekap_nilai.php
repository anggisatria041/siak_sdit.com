<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">
                   Rekap Nilai
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
                                Rekap Nilai
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
                                    Manage Data Nilai<p id="hax"></p>
                                </h3>
                            </div>
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
                        <ul class="nav nav-pills nav-fill" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" onclick="renew('lingkup1')" href="#m_tabs_5_1"><b>Lingkup Materi 1</b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" onclick="renew('lingkup2')" href="#m_tabs_5_2"><b>Lingkup Materi 2</b></a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" onclick="renew('lingkup3')" href="#m_tabs_5_3"><b>Lingkup Materi 3</b></a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" onclick="renew('lingkup4')" href="#m_tabs_5_4"><b>Lingkup Materi 4</b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" onclick="renew('lingkup5')" href="#m_tabs_5_5"><b>Lingkup Materi 5</b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" onclick="renew('sumatif')" href="#m_tabs_5_6"><b>Sumatif Nilai</b></a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="m_tabs_5_1" role="tabpanel">
                                <div class="m_datatable" id="lingkup1"></div>
                            </div>
                            <div class="tab-pane" id="m_tabs_5_2" role="tabpanel">
                                <div class="m_datatable" id="lingkup2"></div>
                            </div>
                             <div class="tab-pane" id="m_tabs_5_3" role="tabpanel">
                                <div class="m_datatable" id="lingkup3"></div>
                            </div>
                             <div class="tab-pane" id="m_tabs_5_4" role="tabpanel">
                                <div class="m_datatable" id="lingkup4"></div>
                            </div>
                            <div class="tab-pane" id="m_tabs_5_5" role="tabpanel">
                                <div class="m_datatable" id="lingkup5"></div>
                            </div>
                            <div class="tab-pane" id="m_tabs_5_6" role="tabpanel">
                                <div class="m_datatable" id="sumatif"></div>
                            </div>
                        </div>
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
                        Tambah Nilai
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <table class="table table-bordered">
                    <form class="m-form m-form--fit m-form--label-align-right" action="" method="POST" id="formAdd" enctype="multipart/form-data">
                        <input type="hidden" name="nilai_id" value="">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="" style="display: none">
                        <tr>
                            <th style="width: 15%">NIS</th>
                            <th style="width: 30%">Nama Siswa</th>
                            <th>
                                TP 1
                            </th>
                            <th>
                                TP 2
                            </th>
                            <th>
                                TP 3
                            </th>
                            <th>
                                TP 4
                            </th>
                        </tr>
                        <tr>
                            <td id="nis"></td>
                            <td style="width: 30%" id="nama"></td>
                            <td>
                                <input type="number" name="tp1" required class="form-control m-input" min="0"/>
                            </td>
                            <td>
                                <input type="number" name="tp2" required class="form-control m-input" min="0"/>
                            </td>
                            <td>
                                <input type="number" name="tp3" required class="form-control m-input" min="0"/>
                            </td>
                            <td>
                                <input type="number" name="tp4" required class="form-control m-input" min="0"/>
                            </td>
                        </tr>
                    </form>
                </table>
                <div class="modal-footer">
                    <a href="#" onclick="save()" id="btnSaveAjax" class="btn btn-accent">
                        Simpan
                    </a>
                </div>
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
        $('.m-select2').select2({
            width: '100%'
        });
    }
    function renew(x) {
        reload_table(x);
    }

    function edit(id) {
        
        method = 'edit';
        resetForm();
        $('#btnSaveAjax').show();
        $('#exampleModalLongTitle').html("Edit Nilai");

        $.ajax({
            url: "<?php echo base_url() . 'dir/C_nilai/edit' ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                if (data.data == true) {
                    $('#formAdd')[0].reset();
                    $('[name="nilai_id"]').val(data.nilai_id);
                    $('#nis').html(data.nis);
                    $('#nama').html(data.nama);
                    $('[name="tp1"]').val(data.tp1);
                    $('[name="tp2"]').val(data.tp2);
                    $('[name="tp3"]').val(data.tp3);
                    $('[name="tp4"]').val(data.tp4);
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
    
        $('[name="' + csrfName + '"]').val(csrfHash);
        $.ajax({
            url: "<?= base_url() . 'dir/C_nilai/update' ?>",
            type: "POST",
            data: new FormData($('#formAdd')[0]), //this is formData
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
                    reload_table('lingkup1');
                    reload_table('lingkup2');
                    reload_table('lingkup3');
                    reload_table('lingkup4');
                    reload_table('lingkup5');
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
</script>
<?php
if (isset($lingkup1) && isset($lingkup2) && isset($lingkup3) && isset($lingkup4) && isset($lingkup5) && isset($sumatif)) {
    echo $lingkup1;
    echo $lingkup2;
    echo $lingkup3;
    echo $lingkup4;
    echo $lingkup5;
    echo $sumatif;
}
?>


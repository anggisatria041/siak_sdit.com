<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">
                   Nilai
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
                                Nilai
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
                        <table class="table table-bordered">
                            <form class="m-form m-form--fit m-form--label-align-right" action="" method="POST" id="formAdd" enctype="multipart/form-data">
                                <input type="hidden" name="nilai_id" value="">
                                <input type="hidden" name="mapel_id" value="<?= $mapel_id; ?>">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="" style="display: none">
                                <tr>
                                    <td colspan="7" style="text-align:left; padding-bottom: 10px;">
                                        <div class="d-flex gap-2">
                                            <select name="lingkup_materi" required class="form-control">
                                                <option value="">Pilih Lingkup</option>
                                                <option value="1">Lingkup Materi 1</option>
                                                <option value="2">Lingkup Materi 2</option>
                                                <option value="3">Lingkup Materi 3</option>
                                                <option value="4">Lingkup Materi 4</option>
                                                <option value="5">Lingkup Materi 5</option>
                                            </select>
                                            <select name="semester" required class="form-control">
                                                <option value="">Pilih Semester</option>
                                                <option value="1">Semester 1</option>
                                                <option value="2">Semester 2</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
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
                                <?php $i = 1; ?>
                                <?php foreach ($siswa as $row) : ?>
                                <tr>
                                    <td><?= $i; ?></td>
                                    <td><?= $row['nis']; ?></td>
                                    <td style="width: 30%"><?= $row['nama']; ?></td>
                                    <td>
                                        <input type="number" name="tp1[<?= $row['siswa_id']; ?>]" required class="form-control m-input"/>
                                    </td>
                                    <td>
                                        <input type="number" name="tp2[<?= $row['siswa_id']; ?>]" required class="form-control m-input"/>
                                    </td>
                                    <td>
                                        <input type="number" name="tp3[<?= $row['siswa_id']; ?>]" required class="form-control m-input"/>
                                    </td>
                                    <td>
                                        <input type="number" name="tp4[<?= $row['siswa_id']; ?>]" required class="form-control m-input"/>
                                    </td>
                                </tr>
                                <?php endforeach ?>
                            </form>
                        </table>
                        <div class="modal-footer">
                            <a href="#" onclick="save()" id="btnSaveAjax" class="btn btn-accent">
                                Simpan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var method = '';
    var csrfName = '<?= $this->security->get_csrf_token_name(); ?>',
        csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

    function resetForm() {
        $('#m_form_1_msg').hide();
        $('#formAdd')[0].reset();
        $('[name="lingkup_materi"] :selected').removeAttr('selected');
        $('.m-select2').select2({
            width: '100%'
        });
    }
    function save() {
       
        $('[name="' + csrfName + '"]').val(csrfHash);
        $.ajax({
            url: "<?= base_url() . 'dir/C_nilai/add' ?>",
            type: "POST",
            data: new FormData($('#formAdd')[0]), //this is formData
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            dataType: "JSON",
            success: function(data) {
                if (data.status == 'success') {
                    Swal.fire({
                        title: 'Berhasil..',
                        text: 'Nilai berhasil disimpan',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = "<?= base_url() ?>dir/C_nilai";
                    });
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
<!-- end:: Body -->

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .calendar {
        background-color: #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        padding: 20px;
        width: 900px;
        margin-left: 100px;

    }

    .calendar h2 {
        text-align: center;
        color: #4a4a4a;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #6a89cc;
        color: white;
    }

    td {
        font-size: 18px;
    }

    .checkmark {
        color: #28a745;
    }

    .cross {
        color: #e74c3c;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:last-child td {
        border-bottom: none;
    }
</style>
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
                        <div class="calendar">
                            <h2><?= $namabulan ?></h2>
                            <table>
                                <thead>
                                    <tr>
                                        <th>S</th>
                                        <th>M</th>
                                        <th>T</th>
                                        <th>W</th>
                                        <th>T</th>
                                        <th>F</th>
                                        <th>S</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_1'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_1'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_1'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_1'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <? if ($data['kehadiran_2'] == 'hadir') { ?>
                                                <? echo ✔ ?>
                                            <? } else if ($data['kehadiran_2'] == 'sakit') { ?>
                                                <? echo Sakit ?>
                                            <? } else if ($data['kehadiran_2'] == 'alfa') { ?>
                                                <? echo ❌ ?>
                                            <? } else { ?>
                                                <?= $data['kehadiran_2'] ?>
                                            <? } ?>
                                        </td>
                                        <td class="checkmark">
                                            <? if ($data['kehadiran_3'] == 'hadir') { ?>
                                                <? echo ✔ ?>
                                            <? } else if ($data['kehadiran_3'] == 'sakit') { ?>
                                                <? echo Sakit ?>
                                            <? } else if ($data['kehadiran_3'] == 'alfa') { ?>
                                                <? echo ❌ ?>
                                            <? } else { ?>
                                                <?= $data['kehadiran_3'] ?>
                                            <? } ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>6</td>
                                        <td>7</td>
                                        <td>8</td>
                                        <td>9</td>
                                        <td>10</td>
                                    </tr>
                                    <tr>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_4'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_4'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_4'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_4'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_5'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_5'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_5'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_5'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_6'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_6'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_6'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_6'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_7'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_7'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_7'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_7'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_8'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_8'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_8'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_8'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_9'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_9'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_9'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_9'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_10'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_10'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_10'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_10'] ?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>11</td>
                                        <td>12</td>
                                        <td>13</td>
                                        <td>14</td>
                                        <td>15</td>
                                        <td>16</td>
                                        <td>17</td>
                                    </tr>
                                    <tr>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_11'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_11'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_11'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_11'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_12'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_12'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_12'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_12'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_13'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_13'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_13'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_13'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_14'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_14'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_14'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_14'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_15'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_15'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_15'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_15'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_16'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_16'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_16'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_16'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_17'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_17'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_17'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_17'] ?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>

                                        <td>18</td>
                                        <td>19</td>
                                        <td>20</td>
                                        <td>21</td>
                                        <td>22</td>
                                        <td>23</td>
                                        <td>24</td>
                                    </tr>
                                    <tr>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_18'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_18'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_18'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_18'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_19'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_19'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_19'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_19'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_20'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_20'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_20'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_20'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_21'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_21'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_21'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_21'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_22'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_22'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_22'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_22'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_23'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_23'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_23'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_23'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_24'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_24'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_24'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_24'] ?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>25</td>
                                        <td>26</td>
                                        <td>27</td>
                                        <td>28</td>
                                        <td>29</td>
                                        <td>30</td>
                                        <td>31</td>
                                    </tr>
                                    <tr>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_25'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_25'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_25'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_25'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_26'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_26'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_26'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_26'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_27'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_27'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_27'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_27'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_28'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_28'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_28'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_28'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_29'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_29'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_29'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_29'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_30'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_30'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_30'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_30'] ?>
                                            <?php } ?>
                                        </td>
                                        <td class="checkmark">
                                            <?php if ($data['kehadiran_31'] == 'hadir') { ?>
                                                ✔
                                            <?php } else if ($data['kehadiran_31'] == 'sakit') { ?>
                                                    Sakit
                                            <?php } else if ($data['kehadiran_31'] == 'alfa') { ?>
                                                        ❌
                                            <?php } else { ?>
                                                <?= $data['kehadiran_31'] ?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!--end: Datatable -->
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
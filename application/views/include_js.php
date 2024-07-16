<!--begin::Base Scripts -->
<script src="<?= base_url(); ?>assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
<script src="<?= base_url(); ?>assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script>
<!-- <script src="<?= base_url(); ?>assets/demo/default/custom/crud/datatables/extensions/buttons.js" type="text/javascript"></script> -->
<!-- <script src="<?= base_url(); ?>assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script> -->
<!-- <script src="<?= base_url(); ?>assets/demo/default/custom/crud/forms/widgets/dropzone.js" type="text/javascript"></script> -->
<script src="<?= base_url(); ?>assets/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="<?= base_url(); ?>assets/demo/default/custom/crud/forms/widgets/bootstrap-timepicker.js" type="text/javascript"></script>
<script src="<?= base_url(); ?>assets/demo/default/custom/crud/forms/widgets/bootstrap-daterangepicker.js" type="text/javascript"></script>
<script src="<?= base_url(); ?>assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>

<!-- end::Base Scripts -->

<script type="text/javascript">
    domReady(() => {

        $('.tanggal').datepicker({
            todayHighlight: !0,
            autoclose: true,
            format: "dd-M-yyyy"
        });

        $('.timestamp').datetimepicker({
            todayHighlight: !0,
            format: "dd-M-yyyy hh:ii"
        });
        $('.tahun').datepicker({
            format: "yyyy",
            autoclose: true,
            minViewMode: "years"
        });
        $('.bulan').datepicker({
            format: "m",
            autoclose: true,
            minViewMode: "months"
        });

        $(".timeizin").timepicker({
            minuteStep: 1,
            defaultTime: "",
            showSeconds: !0,
            showMeridian: !1,
            snapToStep: !0
        });

        $('.datenotnextdate').datepicker({
            endDate: new Date(),
            todayHighlight: !0,
            format: "dd-M-yyyy"
        });

        $('.tanggalmultiple').datepicker({
            todayHighlight: !0,
            format: "dd-M-yyyy",
            multidate: true
        });
        $(".m_selectpicker").selectpicker();
        $(".m-select2").select2({
            width: "100%"
        });
        $(".uang_idr").inputmask("Rp 999.999.999.999", {
            numericInput: !0
        });
        $(".uang_usd").inputmask("$ 999.999.999.999", {
            numericInput: !0
        });
        $(".integer").inputmask('999.999.999.999', {
            numericInput: !0
        });
        $(".decimal").inputmask("99 %", {
            min: 0,
            max: 100
        });

        $(".uang").inputmask("999.999.999", {
            numericInput: !0
        });

        $(".currency").inputmask('decimal', {
            radixPoint: ".",
            autoGroup: true,
            groupSeparator: ",",
            groupSize: 3,
            autoUnmask: true,
            rightAlign: false,
            autoUnmask: true,
        });
    });
</script>
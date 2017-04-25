<script type="text/javascript">
window.__token = <?php echo "'". $_POST['accessToken'] ."'"; ?>;
</script>

<base href="/"> 
<link href="/css/eup/with-bootstrap.min.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="app/lib/components-font-awesome/css/font-awesome.min.css">
<link href="/css/eup/master.css" type="text/css" rel="stylesheet" />
<div data-ng-app="eupapp" class="wk-page-content index-plain-page">
    <ui-view>
    </ui-view>
</div>
<!-- Libraries & Angular Dependent Module Files Starts Here-->
<script src="app/lib/angular/angular.min.js"></script>
<script src="app/lib/angular-google-chart/ng-google-chart.js"></script>
<script src="app/lib/angular-ui-router/release/angular-ui-router.min.js"></script>
<script src="app/lib/angular-smart-table/dist/smart-table.min.js"></script>
<script src="app/lib/angular-messages/angular-messages.min.js"></script>
<script src="app/lib/angular-translate/angular-translate.min.js"></script>
<script src="app/lib/angular-translate-loader-static-files/angular-translate-loader-static-files.min.js"></script>
<script src="app/lib/ngstorage/ngStorage.min.js"></script>
<script src="app/lib/angular-bootstrap/ui-bootstrap.min.js"></script>
<script src="app/lib/angular-bootstrap/ui-bootstrap-tpls.min.js"></script>
<script src="app/lib/angular-jwt/dist/angular-jwt.min.js"></script>
<!-- Libraries & Angular Dependent Module Files Ends Here-->
<!-- Application level files Starts Here-->
<script src="app/env.js"></script>
<script src="app/eupapp.js"></script>
<script src="app/eupapp.route.js"></script>
<script src="app/eupapp.config.js"></script>
<script src="app/common/euphttpinterceptor.service.js"></script>
<script src="app/modules/eup/engine.controller.js"></script>
<script src="app/modules/eup/summary.controller.js"></script>
<script src="app/modules/eup/eup.service.js"></script>
<!-- Application level files Ends Here-->

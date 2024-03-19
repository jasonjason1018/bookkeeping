<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<meta http-equiv="Content-Language" content="zh-TW"> 
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="robots" content="noindex">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>視野科技後台管理介面</title>
<link href="/bookkeeping/assets/css/manager_style.css" rel="stylesheet" type="text/css">
<link href="/bookkeeping/assets/css/fancyBox.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="http://jqueryui.com/resources/demos/style.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style type="text/css">


.manager_tab_all_block{ max-width:90%; margin:0 auto;padding: 0 0 30px 0;}
.outstanding_tab .manager_title{ 
background: #ee9117; cursor:pointer;
background: -webkit-gradient(linear, 0 0, 0 bottom, from(#6ad2e7), to(#a2e3f0));
background: -webkit-linear-gradient(#a2e3f0 ,#6ad2e7);
background: -moz-linear-gradient(#a2e3f0 ,#6ad2e7);
background: -ms-linear-gradient(#a2e3f0 ,#6ad2e7);
background: -o-linear-gradient(#a2e3f0 ,#6ad2e7);
background: linear-gradient(#a2e3f0 ,#6ad2e7);
-pie-background: linear-gradient(#a2e3f0 ,#6ad2e7);
color:#000; 
}
</style>
<style type="text/css">.fancybox-margin{margin-right:15px;}</style></head>
<link rel="stylesheet" href="//unpkg.com/element-plus/dist/index.css" />
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="//unpkg.com/element-plus"></script>
<script src="//unpkg.com/@element-plus/icons-vue"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
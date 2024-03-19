<link rel="stylesheet" href="//unpkg.com/element-plus/dist/index.css" />
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="//unpkg.com/element-plus"></script>
<script src="//unpkg.com/@element-plus/icons-vue"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<link rel="shortcut icon" href="https://enterprise.vision4yes.com/logo.ico" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="/bookkeeping/assets/css/reset.css">
<link rel="stylesheet" type="text/css" href="/bookkeeping/assets/css/frame.css">
<link rel="stylesheet" type="text/css" href="/bookkeeping/assets/css/menu.css">
<link rel="stylesheet" type="text/css" href="/bookkeeping/assets/css/index.css">
<link rel="stylesheet" type="text/css" href="/bookkeeping/assets/css/fancyBox.css">

<link rel="stylesheet" type="text/css" href="/bookkeeping/assets/css/font-awesome.min.css">
<style type="text/css">
    .fancybox-margin {
        margin-right: 0px;
    }
</style>
</head>
<div class="wrap member" id="content">
    <div class="filter-black">
        <header class="header-block">
            <div class="container">
                <a href="" class="logo" itemscope="" itemtype="https://schema.org/LocalBusiness">
                    <img src="/bookkeeping/assets/images/logo-w.png" alt="Vision-Resource">
                </a>
            </div>
        </header>
        <div class="login-block">
            <h2>視野科技記帳平台</h2>
            <div class="container">
                <el-form ref="formRef" :rules="rules" :model="form" :hide-required-asterisk="true">
                    <el-form-item label="帳號" prop="username">
                        <el-input type="text" v-model="form.username">
                    </el-form-item>
                    <el-form-item label="密碼" prop="password">
                        <el-input type="password" v-model="form.password">
                    </el-form-item>
                </el-form>
                <div class="btn-block">
                    <input ref="login_button" class="button btn-finish large" type="button" @click="login(formRef)" value="立即登入">
                </div>
            </div>
            <div class="login-footer">Copyright ©2017~2024 Vision Technology Co., Ltd. All rights reserved.</div>
        </div>
    </div>
</div>

<script src="/bookkeeping/assets/js/jquery-1.10.2.min.js"></script>
<script src="/bookkeeping/assets/js/jquery.min.js"></script>
<script src="/bookkeeping/assets/js/jquery.fancybox.pack.js"></script>
<script src="/bookkeeping/assets/js/matrix.js"></script>
<script src="/bookkeeping/assets/js/_actions.js"></script>
<script>
    const {
        createApp,
        ref,
        onMounted
    } = Vue;
    const { ElMessage } = ElementPlus;
    createApp({
        setup() {
            const login_button = ref();
            const formRef = ref();
            const rules = {
                username: [{
                    required: true,
                    message: '帳號不可為空',
                    trigger: 'blur'
                }, ],
                password: [{
                    required: true,
                    message: '密碼不可為空',
                    trigger: 'blur'
                }, ],
            };
            const form = ref({
                username: '',
                password: '',
            });

            const login = (formRef) => {
                formRef.validate((valid, fields) => {
                    if(valid){
                        axios.post('/user_login', form.value)
                        .then((res) => {
                            if (res.data.code == 500) {
                                ElMessage.error(res.data.msg);
                                form.value = {
                                    username: '',
                                    password: '',
                                }
                                return false;
                            }
                            window.location.href = "/accountForm";
                        })
                        .catch(() => {
                            ElMessage.error('網路異常，請稍後再試');
                        })
                    }
                })
            }

            onMounted(() => {
                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter') {
                        login_button.value.click();
                    }
                });
            });

            return {
                form,
                login,
                login_button,
                formRef,
                rules
            }
        },
    }).use(ElementPlus).mount('#content')
</script>
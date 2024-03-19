@extends('bookkeeping.layout')
@section('content')
<div id="content">
    <div class="outstanding_page">
        <div class="title_txt">
            <center> 帳務詳細資訊 </center>
        </div>
    </div>
    <div class="center manager_tab_all_block">
        <p>&nbsp;</p>
        <div class="form-container">
            <el-descriptions
                direction="horizontal"
                :column="1"
                :size="size"
                border
            >
                <template #title>
                    <el-button type="primary" @click="handlePrev">上一頁</el-button>
                </template>
                <el-descriptions-item label="編號">@{{ form.id }}</el-descriptions-item>
                <el-descriptions-item label="收/支出">@{{ form.type }}</el-descriptions-item>
                <el-descriptions-item label="發票日期">@{{ form.invoice_date }}</el-descriptions-item>
                <el-descriptions-item label="發票類型">@{{ form.invoice_type }}</el-descriptions-item>
                <el-descriptions-item label="內容">@{{ form.content }}</el-descriptions-item>
                <el-descriptions-item label="金額/稅額/進項">
                    <template #default>
                        金額:@{{ form.price }}<br>
                        稅額:@{{ form.tax }}<br>
                        進項:@{{ form.untax }}<br>
                    </template>
                </el-descriptions-item>
                <el-descriptions-item label="實際收/支日期">@{{ form.actual_date??'-' }}</el-descriptions-item>
                <el-descriptions-item label="備注">@{{ form.remark }}</el-descriptions-item>
                <el-descriptions-item label="圖片">
                    <template #default>
                        <el-image v-for="(v, k) in form.img" :src="v.url"></el-image>
                    </template>
                </el-descriptions-item>
                <el-descriptions-item label="是否攤分">
                    <template #default>
                        <el-tag size="small" type="success" v-if="form.share == 1">是</el-tag>
                        <el-tag size="small" type="danger" v-else>否</el-tag>
                    </template>
                </el-descriptions-item>
                <el-descriptions-item label="攤分起始/結束" v-if="form.share == 1">
                    <template #default>
                        攤分起始:@{{ form.start_share_date }}<br>
                        攤分結束:@{{ form.end_share_date }}<br>
                    </template>
                </el-descriptions-item>
                <el-descriptions-item label="歸帳項目">
                    <el-tag style="margin-right: 5px" size="small" v-for="(v, k) in form.account_type" :key="k">@{{ v }}</el-tag>
                </el-descriptions-item>
            </el-descriptions>
        </div>
    </div>
</div>
<script>
    const id = "{{ $param }}";
    const {
        createApp,
        ref,
        onMounted
    } = Vue;
    createApp({
        setup() {
            const centerDialogVisible = ref(false);
            const form = ref({});
            const dialog = ref({});
            const getAccountDetail = () => {
                axios.post('/getAccountData', {id: id})
                .then((res) => {
                    res.data.img = JSON.parse(res.data.img);
                    res.data.account_type = JSON.parse(res.data.account_type);
                    form.value = res.data;
                })
            }

            const handleDetail = () => {
                window.location.href = "/accountDetail";
            }

            const handleEdit = (id) => {
                window.location.href = `/accountForm/${id}`;
            }

            const dataDelete = (id) => {
                centerDialogVisible.value = true;
                dialog.value.id = id;
            }

            const handleDelete = () => {
                axios.post('/accountDelete', dialog.value)
                    .then((res) => {
                        getAccountList();
                    })
                    .finally(() => {
                        centerDialogVisible.value = false;
                    })
            }

            const handlePrev = () => {
                window.history.back();
            }

            onMounted(() => {
                getAccountDetail();
            })

            return {
                form,
                handleDetail,
                handleEdit,
                handleDelete,
                centerDialogVisible,
                dataDelete,
                handlePrev,
            }
        }
    }).use(ElementPlus).mount('#content')
</script>
@endsection
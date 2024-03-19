@extends('bookkeeping.layout')
@section('content')
<div id="content">
    <div class="outstanding_page">
        <div class="title_txt">
            <center> 帳務資訊 </center>
        </div>
    </div>
    <div class="center manager_tab_all_block">
        <p>&nbsp;</p>
        <div class="form-container">
            <el-table :data="form" border>
                <el-table-column label="編號" prop="id" style="width:100%"></el-table-column>
                <el-table-column label="收/支" prop="type" style="width:100%"></el-table-column>
                <el-table-column label="發票日期" prop="invoice_date" style="width:100%"></el-table-column>
                <el-table-column label="內容" prop="content" style="width:100%"></el-table-column>
                <el-table-column label="金額/稅額/進項" style="width:100%">
                    <template #default="scope">
                        金額:@{{ scope.row.price }}<br>
                        稅額:@{{ scope.row.tax }}<br>
                        進項:@{{ scope.row.untax }}
                    </template>
                </el-table-column>
                <el-table-column label="發票號碼/類型" prop="invoice_type" style="width:100%"></el-table-column>
                <el-table-column label="是否攤分" style="width:100%">
                    <template #default="scope">
                        <span v-if="scope.row.share == 1">
                            是
                        </span>
                        <span v-else>
                            否
                        </span>
                    </template>
                </el-table-column>
                <el-table-column label="攤分起始/結束日期" style="width:100%">
                    <template #default="scope">
                        起始:@{{ scope.row.start_share_date??'-' }} <hr> 結束:@{{ scope.row.end_share_date??'-' }}
                    </template>
                </el-table-column>
                <el-table-column label="實際收/支日期" prop="actual_date" style="width:100%"></el-table-column>
                <el-table-column>
                    <template #default="scope">
                        <center><el-button size="small" type="success" @click="handleDetail(scope.row.id)">詳細</el-button><br></center>
                        <center><el-button size="small" type="primary" @click="handleEdit(scope.row.id)">編輯</el-button><br></center>
                        <center><el-button size="small" type="danger" @click="dataDelete(scope.row.id)">刪除</el-button></center>
                    </template>
                </el-table-column>
            </el-table>
            <el-dialog v-model="centerDialogVisible" :title="確認刪除" align-center>
                    <center>刪除後將無法恢復，確定刪除?</center>
                <template #footer>
                    <span class="dialog-footer">
                        <el-button @click="centerDialogVisible = false">取消</el-button>
                        <el-button type="primary" @click="handleDelete">
                            確認
                        </el-button>
                    </span>
                </template>
            </el-dialog>
        </div>
    </div>
</div>
<script>
    const {
        createApp,
        ref,
        onMounted
    } = Vue;
    createApp({
        setup() {
            const centerDialogVisible = ref(false);
            const form = ref();
            const dialog = ref({});
            const getAccountList = () => {
                axios.post('/getAccountList')
                .then((res) => {
                    console.log(res.data);
                    form.value = res.data;
                })
            }

            const handleDetail = (id) => {
                window.location.href=`/accountDetail/${id}`;
            }

            const handleEdit = (id) => {
                window.location.href=`/accountForm/${id}`;
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

            onMounted(() => {
                getAccountList();
            })

            return {
                form,
                handleDetail,
                handleEdit,
                handleDelete,
                centerDialogVisible,
                dataDelete,
            }
        }
    }).use(ElementPlus).mount('#content')
</script>
@endsection
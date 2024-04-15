@extends('bookkeeping.layout')
@section('content')
<div id="content">
    <div class="outstanding_page">
        <div class="title_txt">
            <center> 應收帳款 </center>
        </div>
    </div>
    <div class="center manager_tab_all_block">
        <el-select v-model="year" @change="getAccountList">
            <el-option
                v-for="item in yearOption"
                :key="item.value"
                :label="item.value"
                :value="item.value"
            >
            </el-option>
        </el-select>
        <p>&nbsp;</p>
        <div class="form-container">
            <el-table :data="form" border>
                <el-table-column label="內容" prop="content" style="width:100%"></el-table-column>
                <el-table-column label="發票編號" prop="invoice_number" style="width:100%"></el-table-column>
                <el-table-column label="應收帳款" style="width:100%">
                    <template #default="scope">
                        金額:@{{ scope.row.price }}<br>
                        稅額:@{{ scope.row.tax }}<br>
                        進項:@{{ scope.row.untax }}
                    </template>
                </el-table-column>
                <el-table-column label="發票日期" prop="invoice_date" style="width:100%"></el-table-column>
                <el-table-column label="實際收款金額" style="width:100%">
                    <template #default="scope">
                        <span v-if="scope.row.actual_amount == 0">
                            未收款
                        </span>
                        <span v-else>
                            @{{ scope.row.actual_amount }}
                        </span>
                    </template>
                </el-table-column>
                <el-table-column label="實際收款日期" prop="actual_date" style="width:100%"></el-table-column>
                <el-table-column label="備注" prop="remark" style="width:100%"></el-table-column>
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
                axios.post('/getAccountListIncome', {year:year.value})
                .then((res) => {
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

            const year = ref();
            const yearOption = ref([]);

            const setOption = () => {
                var date = new Date();
                year.value = date.getFullYear()
                for(i=1911;i<=year.value;i++){
                    yearOption.value.push({label:i, value:i});
                }
            }

            onMounted(() => {
                setOption();
                getAccountList();
            })

            return {
                form,
                handleDetail,
                handleEdit,
                handleDelete,
                centerDialogVisible,
                dataDelete,
                getAccountList,
                year,
                yearOption,
            }
        }
    }).use(ElementPlus).mount('#content')
</script>
@endsection
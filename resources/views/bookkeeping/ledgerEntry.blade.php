@extends('bookkeeping.layout')
@section('content')
<div id="content">
    <div class="outstanding_page">
        <div class="title_txt">
            <center> 新增歸帳項目 </center>
        </div>
    </div>
    <div class="center manager_tab_all_block">
        <p>&nbsp;</p>
        <div class="form-container">
            <el-table :data="table_data" border>
                <el-table-column prop="id" label="編號" style="width:100%"></el-table-column>
                <el-table-column prop="item" label="名稱" style="width:100%"></el-table-column>
                <el-table-column prop="create_at" label="新增日期" style="width:100%"></el-table-column>
                <el-table-column prop="update_at" label="最後編輯日期" style="width:100%"></el-table-column>
                <el-table-column style="width:100%">
                    <template #header>
                        <el-button type="success" @click="handleEdit(0)">新增</el-button>
                    </template>
                    <template #default="scope">
                        <el-button type="primary" @click="handleEdit(`${scope.row.id}`)">編輯</el-button>
                        <el-button type="danger" @click="handleDeleteDialog(`${scope.row.id}`)">刪除</el-button>
                    </template>
                </el-table-column>
                <template #append v-if="form.length > 10">
                    <el-pagination :page-sizes="page_config.size" style="justify-content:center" layout=" prev, pager, next" :total="form.length" @change="handlePagination"></el-pagination>
                </template>
            </el-table>
            <p>&nbsp;</p>
            <el-dialog v-model="centerDialogVisible" :title="dialog.title" align-center>
                <span v-if="dialog.type != 'delete'">
                    <el-form>
                        <el-form-item label="項目名稱">
                            <el-input v-model="dialog.item"></el-input>
                        </el-form-item>
                    </el-form>
                </span>
                <span v-else>
                    <center>刪除後將無法恢復，確定刪除?</center>
                </span>
                <template #footer>
                    <span class="dialog-footer" v-if="dialog.type != 'delete'">
                        <el-button @click="centerDialogVisible = false">關閉</el-button>
                        <el-button type="primary" @click="handleSend" :disabled="!dialog.item">
                            送出
                        </el-button>
                    </span>
                    <span class="dialog-footer" v-else>
                        <el-button @click="centerDialogVisible = false">關閉</el-button>
                        <el-button type="primary" @click="handleDelete">
                            確認
                        </el-button>
                    </span>
                </template>
            </el-dialog>
        </div>
    </div>
    <p>&nbsp;</p>
</div>
<script>
    const {
        createApp,
        ref,
        onMounted
    } = Vue;
    createApp({
        setup() {
            const page_config = ref({
                size: 10,
                sizes: [10, 20, 30, 40, 50],
                now_page: 1,
            });

            const centerDialogVisible = ref(false);
            const dialog = ref({});

            const form = ref([]);

            const table_data = ref([]);

            const pagination = () => {
                table_data.value = [];
                const start = (page_config.value.now_page - 1) * page_config.value.size;
                const end = page_config.value.now_page * page_config.value.size - 1;
                form.value.forEach((v, k) => {
                    if (k >= start && k <= end) {
                        table_data.value.push(v);
                    }
                })
            }

            const handlePagination = (e) => {
                page_config.value.now_page = e;
                pagination();
            }

            const handleSend = () => {
                console.log(dialog.value);
                axios.post('/saveLedgerEntry', dialog.value)
                    .then((res) => {
                        centerDialogVisible.value = false;
                        getLedgerEntryList();
                    });
            }

            const handleDeleteDialog = (id) => {
                dialog.value = {};
                dialog.value.title = '刪除確認';
                dialog.value.id = id;
                dialog.value.type = 'delete';
                centerDialogVisible.value = true;
            }

            const handleDelete = () => {
                axios.post('/ledgerEntryDelete', dialog.value)
                    .then((res) => {
                        centerDialogVisible.value = false;
                        getLedgerEntryList();
                    })
            }

            const handleEdit = (id) => {
                dialog.value = {};
                centerDialogVisible.value = true
                dialog.value.title = "新增歸帳項目";
                if (id) {
                    const data = form.value.filter(row => row.id == id);
                    dialog.value.item = data[0].item;
                    dialog.value.id = data[0].id;
                    dialog.value.title = "編輯歸帳項目";
                }
            }

            const getLedgerEntryList = () => {
                axios.post('/getLedgerEntryList')
                    .then((res) => {
                        form.value = res.data;
                    })
                    .finally(() => {
                        pagination();
                    })
            }

            onMounted(() => {
                getLedgerEntryList();
            })

            return {
                form,
                table_data,
                page_config,
                handlePagination,
                centerDialogVisible,
                dialog,
                handleSend,
                handleEdit,
                handleDelete,
                handleDeleteDialog,
            }
        },
    }).use(ElementPlus).mount('#content')
</script>
@endsection
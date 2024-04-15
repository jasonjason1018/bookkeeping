@extends('bookkeeping.layout')
@section('content')
<div id="content">
    <div class="outstanding_page">
        <div class="title_txt">
            <center> 記帳表單 </center>
        </div>
    </div>
    <div class="center manager_tab_all_block">

        <p>&nbsp;</p>
        <div class="form-container">
            <el-form
                :rules="rules"
                ref="ruleFormRef"
                :model="form"
            >
                <el-form-item  v-if="id">
                        <el-button type="primary" @click="handlePrev">上一頁</el-button>
                </el-form-item>
                <el-form-item label="收入/支出">
                    <el-select v-model="form.type" @change="changeType">
                        <el-option
                            v-for="item in type"
                            :key="item.value"
                            :label="item.label"
                            :value="item.label"
                        >
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="收/支類別" prop="category_type">
                    <el-select v-model="form.category_type">
                        <el-option
                            v-for="item in category_type"
                            :key="item.id"
                            :label="item.name"
                            :value="item.name"
                        >
                        </el-option>
                    </el-select>
                </el-form-item>
                <div v-if="form.invoice_type != '其他收據'">
                    <el-form-item label="發票編號" prop="invoice_number">
                        <el-input placeholder="ex.AA12345678" v-model="form.invoice_number">
                    </el-form-item>
                    <el-form-item label="發票日期" prop="invoice_date">
                        <el-date-picker
                            style="width: 100%;"
                            v-model="form.invoice_date"
                            type="date"
                            placeholder="選擇日期"
                            format="YYYY-MM-DD"
                            value-format="YYYY-MM-DD"
                        >
                    </el-form-item>
                </div>
                <el-form-item label="發票類型">
                    <el-select v-model="form.invoice_type" @change="calculation(form.price)">
                        <el-option
                            v-for="item in invoice_type"
                            :key="item.value"
                            :label="item.value"
                            :value="item.value"
                        >
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="內容">
                    <el-input type="textarea" v-model="form.content">
                </el-form-item>
                <el-form-item label="計算稅額" v-if="form.invoice_type == '其他收據'">
                    <el-checkbox v-model="is_count" @click="handleInput(form.price)">
                    </el-checkbox>
                </el-form-item>
                <el-form-item label="金額" prop="price">
                    <el-input
                    :formatter="(value) => value.replace(/[^0-9.,]/g, '')"
                    v-model="form.price"
                    @input="handleInput"
                    style="width:100%"
                >
                </el-input>
                </el-form-item>
                <el-form-item label="稅額">
                    <el-input v-model="form.tax" :disabled="true">
                </el-form-item>
                <el-form-item label="進項">
                    <el-input v-model="form.untax" :disabled="true">
                </el-form-item>
                <el-form-item label="實際收/支日期" prop="actual_date">
                    <el-date-picker
                        style="width: 100%;"
                        v-model="form.actual_date"
                        type="date"
                        placeholder="選擇日期"
                        format="YYYY-MM-DD"
                        value-format="YYYY-MM-DD"
                    >
                </el-form-item>
                <el-form-item :label="actual_amount_label" >
                    <el-input
                    :formatter="(value) => value.replace(/[^0-9.,]/g, '')"
                    v-model="form.actual_amount"
                    style="width:100%"
                    >
                </el-form-item>
                <el-form-item label="備註">
                    <el-input type="textarea" v-model="form.remark">
                </el-form-item>
                <el-form-item label="上傳圖片">
                    <el-upload
                        action="#"
                        class="upload-demo"
                        :headers="{ 'X-CSRF-TOKEN': csrfToken , 'Content-Type': 'multipart/form-data'}"
                        v-model:file-list="form.img"
                        :auto-upload="false"
                        list-type="picture"
                    >
                        <el-button type="primary">上傳圖片</el-button>
                        <template #tip>
                            <!-- <div class="el-upload__tip">
                                jpg/png files with a size less than 500KB.
                            </div> -->
                        </template>
                    </el-upload>
                </el-form-item>
                <el-form-item label="是否攤分" prop="share">
                    <el-radio-group v-model="form.share" class="ml-4">
                        <el-radio label="1" size="large">是</el-radio>
                        <el-radio label="0" size="large">否</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="開始日期" v-if="form.share == 1" prop="start_share_date">
                    <el-date-picker
                        style="width: 100%;"
                        v-model="form.start_share_date"
                        type="date"
                        placeholder="選擇日期"
                        format="YYYY-MM-DD"
                        value-format="YYYY-MM-DD"
                    >
                </el-form-item>
                <el-form-item label="結束日期" v-if="form.share == 1" prop="end_share_date">
                    <el-date-picker
                        style="width: 100%;"
                        v-model="form.end_share_date"
                        type="date"
                        placeholder="選擇日期"
                        format="YYYY-MM-DD"
                        value-format="YYYY-MM-DD"
                    >
                </el-form-item>
                <el-form-item label="歸帳項目" prop="account_type">
                    <el-select
                        v-model="form.account_type"
                        placeholder="請選擇"
                        @change="getSubject"
                    >
                        <el-option
                            v-for="item in account_type_options"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value"
                        ></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="歸帳科目" prop="subject" v-if="form.account_type">
                    <el-select
                        v-model="form.subject"
                        placeholder="請選擇"
                    >
                        <el-option
                            v-for="item in subject"
                            :key="item.value"
                            :label="item.name"
                            :value="item.name"
                        ></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="handlePrev" v-if="id">上一頁</el-button>
                    <el-button type="primary" @click="send(ruleFormRef)">送出</el-button>
                </el-form-item>
            </el-form>
        </div>
        <p>&nbsp;</p>
    </div>
</div>
    <script>
        const id = "{{ $param??false }}";
        const {
            createApp,
            ref,
            onMounted
        } = Vue;
        createApp({
            setup() {
                const is_count = ref(false);
                const ruleFormRef = ref();
                const actual_amount_label = ref('實際收入金額');
                const rules = {
                    category_type: [
                        {required:true, message:'請選擇至收/支類別', trigger: 'change'},
                    ],
                    invoice_number:[
                        {required:true, message:'請填寫發票號碼', trigger: 'blur'},
                        { pattern: /^[a-zA-Z]{2}[0-9]{8}$/, message: '發票編號格式不正確', trigger: 'blur' },
                    ],
                    invoice_date:[
                        {required:true, message:'請選擇發票日期', trigger: 'change'}
                    ],
                    price: [
                        { required: true, message: '請輸入金額', trigger: 'blur' },
                    ],
                    share: [
                        {required:true, message:'請選擇是否攤分', trigger: 'change'}
                    ],
                    start_share_date:[
                        {required:true, message:'請選擇攤分起始日期', trigger: 'change'}
                    ],
                    end_share_date:[
                        {required:true, message:'請選擇攤分結束日期', trigger: 'change'}
                    ],
                    account_type: [
                        {required:true, message:'請選擇至少一個歸帳項目', trigger: 'change'}
                    ],
                    subject: [
                        {required:true, message:'請選擇至少一個歸帳科目', trigger: 'change'}
                    ],
                };

                const account_type_options = ref([]);

                const type = ref({
                    0:{value: '0', label:'收入'},
                    1:{value: '1', label:'支出'},
                });

                const invoice_type = ref({
                    0:{value: '三聯發票'},
                    1:{value: '二聯發票'},
                    2:{value: '其他收據'},
                });

                const form = ref({
                    type:'支出',
                    invoice_type: '三聯發票',
                    share: '0',
                    content: '',
                    remark: '',
                });

                const handleInput = (value)  => {
                    const thisValue = value.replace(/[^0-9]/g, '');
                    if(thisValue != ''){
                        calculation(thisValue);
                    }
                }

                const calculation = (thisValue) =>{
                    if(thisValue != '' && thisValue != undefined){
                        form.value.tax = 0;
                        form.value.untax = thisValue;
                        if(form.value.invoice_type != '其他收據' || is_count.value == true){
                            countTax(thisValue);
                        }
                    }
                }

                const countTax = (thisValue) => {
                    form.value.untax = Math.round(thisValue/1.05);
                    form.value.tax = thisValue - form.value.untax;
                }
                const imgList = ref([]);
                const send = (formRef) => {
                    formRef.validate((valid, fields) => {
                        if(valid){
                            uploadImg();
                        }
                    })
                }

                const uploadImg = () => {
                    if(form.value.img != undefined && form.value.img.length > 0){
                        form.value.img.forEach((row, key) =>{
                            if(row.raw != ''){
                                const formData = new FormData();
                                formData.append('file', row.raw, row.name);
                                axios.post('/uploadImg', formData, {
                                    headers: {
                                        'Content-Type': 'multipart/form-data'
                                    }
                                })
                                .then(response => {
                                    imgList.value.push({name: response.data, raw: '', url: `/uploads/${response.data}`})
                                })
                                .catch(error => {
                                    console.error('Error uploading file:', error);
                                })
                                .finally(() => {
                                    if(form.value.img.length == key+1){
                                        sendFormData();
                                    }
                                });
                            }else{
                                imgList.value.push(row);
                                if(key == form.value.img.length-1){
                                    sendFormData();
                                }
                            }
                        });
                    }else{
                        sendFormData(); 
                    }
                }

                const sendFormData = () => {
                    form.value.account_type = JSON.stringify(form.value.account_type);
                    form.value.imgList = JSON.stringify(imgList.value)
                    axios.post('/saveForm', form.value, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then((res) => {
                        if(id){
                            handlePrev();
                            return false;
                        }
                        window.location.reload();
                    })
                }

                const getledgerEntryList = () => {
                    axios.post('/getledgerEntryList')
                    .then((res) => {
                        res.data.forEach((v, k) => {
                            account_type_options.value.push({label: v.item, value: v.id});
                        });
                    })
                }

                const getAccountData = () => {
                    axios.post('/getAccountData', {id: id})
                    .then((res) => {
                        res.data.img = JSON.parse(res.data.img);
                        res.data.account_type = JSON.parse(res.data.account_type);
                        form.value = res.data;
                        console.log(form.value);
                    })
                }

                const handlePrev = () => {
                    window.history.back();
                }

                const getCompanyId = () => {
                    axios.post('/getCompanyId')
                    .then((res) => {
                        form.value.company_id = res.data;
                    })
                }

                const category_type = ref();

                const getCategoryType = () => {
                    axios.post('/getCategoryType')
                    .then((res) => {
                        category_type.value = res.data;
                    })
                }

                const changeType = (value) => {
                    actual_amount_label.value = value == '支出'?'匯費':'實際收入金額';
                }
                const subject = ref();
                const getSubject = () => {
                    form.value.subject = '';
                    axios.post('/getSubject', {id: form.value.account_type})
                        .then((res) => {
                            subject.value = res.data;
                        })
                }

                onMounted(() => {
                    getledgerEntryList();
                    getCompanyId();
                    getCategoryType();
                    // getSubject();
                    if(id){
                        getAccountData();
                    }
                })

                return {
                    form,
                    send,
                    type,
                    invoice_type,
                    handleInput,
                    calculation,
                    csrfToken:document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    account_type_options,
                    rules,
                    ruleFormRef,
                    id,
                    handlePrev,
                    category_type,
                    is_count,
                    actual_amount_label,
                    changeType,
                    getSubject,
                    subject,
                }
            },
        }).use(ElementPlus).mount('#content')
    </script>
    <style>
        .form-container {
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 10px;
        }
        input[type=file]{
            display:none
        }
    </style>
@endsection
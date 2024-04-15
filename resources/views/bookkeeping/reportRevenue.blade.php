@extends('bookkeeping.layout')
@section('content')
<div id="content">
    <div class="outstanding_page">
        <div class="title_txt">
            <center> 營收分攤 </center>
        </div>
    </div>
    <div class="center manager_tab_all_block">
        <el-select v-model="year" @change="getData">
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
            
            const year = ref();
            const yearOption = ref([]);

            const setOption = () => {
                var date = new Date();
                year.value = date.getFullYear()
                for(i=1911;i<=year.value;i++){
                    yearOption.value.push({label:i, value:i});
                }
            }

            const getData = () => {
                axios.post('/getReportRevenue', {year:year.value})
                .then((res) => {
                    console.log(res.data);
                })
            }
            onMounted(() => {
                setOption();
                getData();
            })
            return {
                year,
                yearOption,
                getData,
            }
        },
    }).use(ElementPlus).mount('#content')
</script>
@endsection
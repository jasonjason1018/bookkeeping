<form action="/importExcelToDatabase" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="excel_file">
    <input type="submit" value="Upload">
</form>

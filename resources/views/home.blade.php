<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<div class="container">
<h1>轉換短網址</h1>
<form action="/" method="post" id="input_div">
	@csrf
    <div id="input_div">
        <div class="mb-3" >
            <input type="text" class="form-control"  name="original_address[]"  >
        </div>
    </div>
    <button type="button" class="btn btn-success" onclick="addShortUrl()">再新增一筆網址</button>
	<input type="submit" class="btn btn-danger" value="轉換網址">
</form>
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@if(isset($links))
	<div class="result">
        @foreach($links as $link)
		<p>您所轉換的短網址:<a 
			href="{{url($link->short_url)}}">
			{{url($link->short_url)}}</a>
		</p>
        @endforeach
	</div>
@endif
</div>
<script>
function addShortUrl(){
    var parent = document.getElementById('input_div');
    var newChild = `
    <div class="mb-3 row">
        <div class="col-sm-10">
            <input type="text" class="form-control" name="original_address[]">
        </div>
        <div class="col-sm-2">
            <button type="button" class="btn btn-danger" onclick="this.parentNode.parentNode.remove()">-</button>
        </div>
    </div>`;
    parent.insertAdjacentHTML('afterbegin', newChild);
}
</script>
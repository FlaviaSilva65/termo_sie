
<div class="container my-4">

  <div class="input-group pb-0">

    <input type="text" class="form-control border border-secondary border-2 rounded-start" id="nomeList" placeholder="" onchange="getId()" aria-label="Input group example" aria-describedby="btnGroupAddon">
    <div class="input-group-text border border-secondary border-2 bi bi-search bg-success text-white " id="btnGroupAddon"></div>
  </div>

</div>

<div class="col-10 w-100" id="tableAjax">
  <?= $this->Element('usuarioIndexSearch') ?>
</div>

<script>
  $('document').ready(function() {
    $('#nomeList').keyup(function() {
      var searchkey = $(this).val();
      searchTags(searchkey);
    });

    function searchTags(keyword) {
      var data = keyword;
      if (!data) data = 'A';
      $.ajax({
        method: 'get',
        url: "<?php echo $this->Url->build(['controller' => 'Usuarios', 'action' => 'search']); ?>",
        data: {
          keyword: data,

        },
        success: function(response) {
          // alert('a')
          console.log(response);
        },
        error: function(response) {
          alert('b')

        },
        complete: function(response) {
          $('#tableAjax').html(response.responseText);
        }
      })
    }
  });
  nomeList = document.getElementById('nomeList');
  nomeList.value = "";

  function getId() {
        var sel = document.getElementById('nomeList').value;
        // alert (sel);
  }
</script>
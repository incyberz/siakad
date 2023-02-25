<style>
  .tb_list_data{
    font-size: small;
  }
  .editable{
    cursor: pointer;
    background-color: #ccffccaa !important;
    transition: .2s;
  }
  .editable:hover{
    background: linear-gradient(#fcf,#faf) !important;
    letter-spacing: 1px;
    color: blue !important;
    font-weight: bold;
  }
</style>

<script>
  $(document).on("click",".editable",function(){
    let tid = $(this).prop("id");
    let rid = tid.split('__');
    let tabel = 'tb_'+rid[1];
    let kolom = rid[2];
    let id = rid[3].trim();

    console.log(`tabel:${tabel} kolom:${kolom} id:${id}`)

    let isi = $(this).text();
    let isi2 = prompt('Data baru:',isi);
    if(!isi2 || isi2.trim().length==0 || isi==isi2) return;


    let link_ajax = `../../ajax/ajax_global/ajax_global_update.php?table=t_${rid[1]}&field=${kolom}&acuan=id_${rid[1]}&acuan_val=${id}&field_val=${isi2}`;

    $.ajax({
      url: link_ajax,
      success: function(a){
        console.log(`ajax reply:${a}`)
        if(a.trim()=='sukses'){
          $('#'+tid).text(isi2);
        }else{
          alert(`AJAX Error. \n\n${a}`)          
        }
      }
    })


  })
</script>
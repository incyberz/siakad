<style>
  .blok_opsi{
    display:grid;
    grid-template-columns: 20px auto 80px;
    grid-gap: 10px;
    /* margin: 10px 0; */
    border-radius: 10px;
    padding: 5px 10px;
    transition: .2s;
  }
  .blok_opsi:hover{
    border: solid 1px #00f;
    background: #fcf
  }
  .nav_soal{
    position:sticky;
    top: 30px;
    background:white;
    padding: 5px;
    margin-bottom: 10px;
  }
  .nav_soal_item{
    display: inline-block;
    width: 25px;
    /* background:#ccf; */
    font-size: small;
    text-align:center;
    cursor:pointer;
    border-radius: 3px;
  }
  .nav_soal_item:hover{
    background: #fcf;
  }
  .blok_upload{
    display:grid;
    grid-template-columns: auto 80px;
    grid-gap: 7px;
  }
  .footer_soal{
    display:grid;
    grid-template-columns: auto 80px 80px;
    grid-gap: 7px;
    border-top: solid 1px #ccc;
    padding-top: 10px;
  }
  .img_soal{
    max-width: 100%;
    max-height: 400px;
    margin-bottom: 10px;
    padding: 5px;
    background: white;
    box-shadow: 1px 1px 3px gray;
  }
</style>
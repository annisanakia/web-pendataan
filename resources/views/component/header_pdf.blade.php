<style type="text/css">
    @page {
        margin: 25px 25px 25px 25px;
    }
    h1.bigheader {
        font-size: 20px;
        margin: 0px;
    }
    h2.bigheader {
        font-size: 18px;
        margin: 0px;
    }
    .subheader {
        font-size: 14px;
        margin: 0px;
    }
    table.list_content {
        border-collapse: collapse;
        margin-top: 10px;
    }
    table.list_content td,
    table.list_content th {
        border: 1px solid black;
        padding: 2px 4px;
        vertical-align: top;
    }
    .list_content th {
        background-color: #fcfcfd;
        text-align: center;
    }
    .list_content td { 
        text-align: center;
    }
    .text-center{
        text-align:center !important
    }
    .text-end{
        text-align:right !important
    }
</style>
<?php
$user = \Auth::user();
?>
<table width="100%">
    <tr>
        <td class="text-center">
            <h1 class="bigheader">{{$title_head_export}}</h1>
            <p class="subheader">
                {{ dateToIndo(date('Y-m-d')) }}
            </p>
        </td>
    </tr>
</table>

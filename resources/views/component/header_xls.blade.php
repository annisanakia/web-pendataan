<style type="text/css">
    .text-center{
        text-align:center !important
    }
    .text-end{
        text-align:right !important
    }
</style>
<table width="100%">
    <tr>
        <td colspan="{{$title_col_sum}}" align="center">
            <p class="bigheader">{{$title_head_export}}</p>
        </td>
    </tr>
    <tr>
        <td colspan="{{$title_col_sum}}" align="center">
            <p class="subheader">
                {{ dateToIndo(date('Y-m-d')) }}
            </p>
        </td>
    </tr>
</table>
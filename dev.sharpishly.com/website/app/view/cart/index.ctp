{{{header}}}

    <!-- h1 -->
    <h1>{{{h1}}}</h1>

    <!-- h2 -->
    <h2>{{{h2}}}</h2>

    <table class="table">
        <tr>
            <th>
                <a {{{filter_records_by_id}}}>Id sort</a>
            </th>
            <th>
                <a>User Id</a>
            </th>
            <th>
                <a>Product Id</a>
            </th>
            <th>
                <a>Created At</a>
            </th>
        </tr>
        {{{records}}}
        <tr>
            <td><a>Previous</a></td>
            <td align="center" colspan="2">
                <a href="#">1</a>
                <a href="#">2</a>
                <a href="#">3</a>
                <a href="#">4</a>
            </td>
            <td align="right"><a {{{next}}}>Next</a></td>
        </tr>
    </table>
{{{footer}}}
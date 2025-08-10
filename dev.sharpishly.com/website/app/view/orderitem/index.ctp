{{{header}}}

    <!-- h1 -->
    <h1>{{{h1}}}</h1>

    <!-- h2 -->
    <h2>{{{h2}}}</h2>

    <table class="table">
        <tr>
            <th>
                <a {{{filter_records_by_id}}}>sort</a>
            </th>
            <th>
                <a>order_id</a>
            </th>
            <th>
                <a>product_id</a>
            </th>
        </tr>
        {{{records}}}
        <tr>
            <td><a>Previous</a></td>
            <td align="center">
                <a href="#">1</a>
                <a href="#">2</a>
                <a href="#">3</a>
                <a href="#">4</a>
            </td>
            <td align="right"><a {{{next}}}>Next</a></td>
        </tr>
    </table>
{{{footer}}}
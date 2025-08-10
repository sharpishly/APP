{{{header}}}

    <!-- h1 -->
    <h1>{{{h1}}}</h1>

    <!-- h2 -->
    <h2>{{{h2}}}</h2>

    <form {{{form}}}>
        <fieldset>

            <table class="table">
                <tr>
                    <td colspan="3"></td>
                    <td>
                        <select name="status">{{{selector}}}</select>
                        <button>Submit</button>
                    </td>
                </tr>
                <tr>
                    <th>
                        <a {{{filter_records_by_id}}}>sort</a>
                    </th>
                    <th>
                        <a>title</a>
                    </th>
                    <th>
                        <a>date</a>
                    </th>
                    <th align="right">
                        <input type="checkbox" />
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
                    <td align="right" colspan="2"><a {{{next}}}>Next</a></td>
                </tr>
            </table>
        </fieldset>
    </form>

{{{footer}}}
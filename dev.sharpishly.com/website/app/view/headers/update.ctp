{{{header}}}

<!-- h1 -->
<h1>{{{h1}}}</h1>

<!-- h2 -->
<h2>{{{h2}}}</h2>

<p>
    <a href="{{{add}}}" class="note-button">Add meta tags</a>
    <a href="{{{details}}}" class="note-button">See meta tags</a>
</p>

<table class="table">
    <form {{{form}}}>
        <tr>
            <td colspan="2">
                <button>Update</button>
            </td>
        </tr>
        {{{fields}}}
        <tr>
            <td>Status</td>
            <td>
                <select name="status">{{{selector}}}</select>
            </td>
        </tr>
    </form>
</table>

{{{footer}}}
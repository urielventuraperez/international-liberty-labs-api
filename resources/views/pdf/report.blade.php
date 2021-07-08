<!DOCTYPE html>
<html>
  <head>
    <style>
    body {
        height: 842px;
        width: 595px;
        /* to centre page on screen*/
        margin-left: auto;
        margin-right: auto;
    },
    table {
      width: 100%;
    }
    
    td,
    th {
      text-align: left;
    }
    </style>
  </head>
  <body>
<table>
    <tr>
      <th width="50%">-</th>
      <th width="50%">
              <p>{{ $patient }}</p>
              <p>{{ $date_of_birth }}</p>
              <p>{{ $created_test }}</p>
              <p>{{ $created_result }}</p>
      </th>
    </tr>
    </table>
    <table>
    <tr>
      <td colspan="2" style="text-align:center" width="100%">{{ $test }}</td>
    </tr>
    <tr>
        <td colspan="2" style="text-align:center" width="100%">{{ $test }}</td>
    </tr>
</table>
<table>
    <tr>
      <th width="33%">
          <h3>Determination</h3>
          <p></p>
      </th>
      <th width="33%">
        <h3>Outcome</h3>
        <p>{{ $outcome }}</p>
      </th>
      <th width="33%">
        <h3>Reference</h3>
        <p>{{ $reference }}</p>
      </th>
    </tr>
</table>
<table>
<tr>
      <th width="80%">
      </th>
      <th width="20%">
        {{ $result }}
        
      </th>
    </tr>
</table>
<table>
<tr>
      <th width="50%">
      </th>
      <th width="50%">
      <img src="/firma.png" style="width: 100px; height: 100px">
      </th>
    </tr>
</table>
  </body>
</html>

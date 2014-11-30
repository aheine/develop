

<!DOCTYPE html>
<html lang="en">
<head>

  <title>Alice Heine's Data Dictionary</title>
	 
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >

	<meta name="description" content="Table" >
        
	<meta name="author" content="Alice Heine" >

	<link href='table.css' rel='stylesheet' type='text/css' media='screen' title="Table" />


</head>
<body id="table">
    
    
    <table>
        <thead>
            <tr>
                <th scope="col">Table</th>
                <th scope="col">Column</th>
                <th scope="col">Type</th>
                <th scope="col">Prec*</th>
                <th scope="col">PK</th>
                
            </tr>
        </thead>

        <tbody>
            
            <tr>
                <td>tblPerson</td>
                <td>fldUsername</td>
                <td>varchar</td>
                <td>30</td>
                <td> pmk </td>

            </tr>
            
            <tr>
                <td>tblPerson</td>
                <td>fldEmail</td>
                <td>varchar</td>
                <td>65</td>
                <td> - </td>

            </tr>
            
            <tr>
                <td>tblPerson</td>
                <td>fnkHouseholdName</td>
                <td>varchar</td>
                <td>30</td>
                <td> - </td>

            </tr>
            
            <tr>
                <td>tblHousehold</td>
                <td>pmkHouseholdName</td>
                <td>char</td>
                <td>30</td>
                <td> PMK </td>

            </tr>
            
            
            <tr>
                <td>tblBills</td>
                <td>fldType</td>
                <td>char</td>
                <td>30</td>
                <td> - </td>

            </tr>
            
            <tr>
                <td>tblBills</td>
                <td>fldPaid</td>
                <td>int</td>
                <td>1</td>
                <td> - </td>

            </tr>
            
            <tr>
                <td>tblBills</td>
                <td>fldLost</td>
                <td>int</td>
                <td>1</td>
                <td> - </td>

            </tr>
            
            <tr>
                <td>tblBills</td>
                <td>fldAmount</td>
                <td>int</td>
                <td>50</td>
                <td> - </td>
                
            </tr>
            
            <tr>
                <td>tblBills</td>
                <td>fldMonth</td>
                <td>char</td>
                <td>50</td>
                <td> - </td>
                
            </tr>
            
            <tr>
                <td>tblBills</td>
                <td>fnkHouseholdName</td>
                <td>char</td>
                <td>30</td>
                <td> - </td>
                
            </tr>
            
            <tr>
                <td>tblBills</td>
                <td>fnkUsername</td>
                <td>varchar</td>
                <td>30</td>
                <td> - </td>
                
            </tr>

        
        </tbody>
    </table>
</body>
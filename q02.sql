SELECT pmkHouseholdName AS 'Household', pmkUsername AS 'User', fldEmail AS 'Email', fldMonth AS 'Month', fldType AS 'Type of Expense', pmkBillName AS 'Name of Expense', fldAmount AS 'Amount', fldPaid AS 'Paid', fldCash AS 'Cash', fldCredit AS 'Credit', fldCheck AS 'Check', fldLost AS 'Lost' FROM tblPerson, tblBills, tblHousehold 

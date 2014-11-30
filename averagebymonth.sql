SELECT fnkHouseholdName AS 'Household', fldMonth AS 'Month', AVG(fldAmount) AS 'Average Expense' FROM tblBills GROUP BY fnkHouseholdName, fldMonth



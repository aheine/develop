SELECT fnkHouseholdName AS 'Household', CAST(AVG(fldAmount)AS DECIMAL (10,2)) AS 'Average Expense' FROM tblBills GROUP BY fnkHouseholdName
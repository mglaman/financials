<?php

/**
 * @file
 * API Documentation.
 */

/**
 * Allows modules to interact with the account transaction table rows.
 *
 * @see AccountTransactions::tableRows()
 * @param $rows
 * @param $accountID
 */
function hook_financials_account_transactions_rows_alter(&$rows, $accountID) {
  // Needs example
}

/**
 * Allows modules to interact with the budget table rows.
 *
 * @see BudgetOverview::tableRows()
 * @param $rows
 */
function hook_financials_budget_item_rows_alter(&$rows) {
  // Needs example
}
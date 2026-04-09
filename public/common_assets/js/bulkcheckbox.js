const checkboxes = document.querySelectorAll('.sub_select input[type="checkbox"]');
const checkAll = document.querySelector('#checkAll');
const bulkActionsBtn = document.querySelector('#bulk-actions-btn');
const selectedrows = document.querySelector('#selectedrows');


// function to check/uncheck all checkboxes
function toggleCheckboxes(checked) {
  checkboxes.forEach((checkbox) => {
    checkbox.checked = checked;
  });
}

// function to handle checkbox click
function handleCheckboxClick() {
  const checkedCheckboxes = document.querySelectorAll('.sub_select input[type="checkbox"]:checked');
  const numChecked = checkedCheckboxes.length;

  // update bulk actions button text
  selectedrows.textContent = numChecked;

  // enable/disable bulk actions button
  if (numChecked > 0) {
    bulkActionsBtn.disabled = false;
  } else {
    bulkActionsBtn.disabled = true;
  }

  // update check all checkbox
  if (numChecked === checkboxes.length) {
    checkAll.checked = true;
  } else {
    checkAll.checked = false;
  }
}

// add event listener to check all checkbox
checkAll.addEventListener('click', () => {
  toggleCheckboxes(checkAll.checked);
  handleCheckboxClick();
});

// add event listeners to checkboxes
checkboxes.forEach((checkbox) => {
  checkbox.addEventListener('click', handleCheckboxClick);
});

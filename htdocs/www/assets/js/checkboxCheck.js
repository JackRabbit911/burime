let allCheckBox = document.querySelector('thead input[type=checkbox]');
let otherCheckBoxes = document.querySelectorAll('tbody input[type=checkbox]');

otherCheckBoxes.forEach(item=>{
item.addEventListener("click", function(){
    let checkBoxes = document.querySelectorAll('.otherCheckbox:checked');
    allCheckBox.checked = checkBoxes.length == otherCheckBoxes.length;
    })
});

allCheckBox.addEventListener("click", function(){
otherCheckBoxes.forEach(item=> item.checked = allCheckBox.checked)
})

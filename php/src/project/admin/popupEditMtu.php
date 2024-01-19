<style>
    .form-popup1 {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      border: 3px solid #f1f1f1;
      z-index: 9;
      padding: 20px;
      background-color: #f1f1f1;
      max-width: 500px;
      width: 100%;
      max-height: 80vh; 
      overflow-y: auto;
      box-shadow: 0 3px 5px rgba(0, 0, 0, 0.5);
      .close-button {
        position: absolute;
        height: 40px; /* Adjust to your preferred height */
        width: 40px; /* Adjust to your preferred width */
        top: 10px;
        right: 10px;
        cursor: pointer;
    }
    }
  </style>

<!-- edit -->
<form  id="mtuEditForm" class="form-popup1"  >
<div class="close-button " onclick="closeForm()">
<a class="btn btn-danger text-white" >
    X
</a> 
    </div>
<div id="mtuEdit">

</div>
   <!-- Container for additional sections -->
   <div class="form-group" id="additionalSectionsContainer1">
        <!-- Additional sections will be appended here -->
    </div>
    <div class="form-group">
    <div class=" d-flex float-end py-4">
<button class="btn btn-primary" style="float: right;" type="button" id="addMt1">เพิ่มรายการ <i class='fas fa-plus'></i></button>

<button class="btn btn-success mx-2 " style='display:none' type="button" id="submitBtn1">บันทึกรายการผลิต <i class='fas fa-file'></i></button>
</div>
        </div>

        
</div>
</form>
<script>
var sectionCounter1 = 0;
var inputSets1 = []; // Array to store references to input sets

document.getElementById('addMt1').addEventListener('click', function () {
    document.getElementById("submitBtn1").style.display = "block";
    sectionCounter1++;
    appendInputSet1(sectionCounter1);
});

function appendInputSet1(counter) {
    var uniqueId = Date.now();

    var newInputContainer = document.createElement('div');
    newInputContainer.className = 'form-group input-set';
    newInputContainer.id = 'inputSet_' + uniqueId;

    var newLabel = document.createElement('div');
    newLabel.className = 'col-sm-12 control-label';
    newLabel.textContent = 'รายการรับที่ ' + counter + ':';

    var newInput = document.createElement('div');
    newInput.className = 'col-sm-12';

    // Append dropdown
    var dropdownId = appendDropdown('e_mt_id1', 'ชื่อวัตถุดิบ(ห้ามเลือกวัตถุดิบซ้ำ!)', newInput);

    // Append input for amount
    var amountId = appendInput('e_amount1', 'จำนวนในการผลิต', newInput, counter);



    newInputContainer.appendChild(newLabel);
    newInputContainer.appendChild(newInput);


        // Append delete button
        var deleteBtn = document.createElement('button');
    deleteBtn.className = 'btn btn-danger text-white my-4';
    deleteBtn.type = 'button';
    deleteBtn.textContent = 'ลบรายการ';

    deleteBtn.addEventListener('click', function () {
        var container = document.getElementById('additionalSectionsContainer1');
        
        // Check if there's at least one input set before attempting to delete
        if (inputSets1.length > 0) {
            var lastInputSet = inputSets1.pop(); // Remove the last element from the array
            container.removeChild(lastInputSet.container);
            sectionCounter--; // Decrement counter when deleting a set
        }
    });
    newInput.appendChild(deleteBtn);

    newInputContainer.appendChild(newLabel);
    newInputContainer.appendChild(newInput);

    document.getElementById('additionalSectionsContainer1').appendChild(newInputContainer);


    // Add the newInputContainer reference to the array
    inputSets1.push({
        container: newInputContainer,
        dropdownId: dropdownId,
        amountId: amountId,
       
    });
    console.log(inputSets1);
}

function appendDropdown(fieldName, label, container) {
    var dropdownContainer = document.createElement('div');
    dropdownContainer.className = 'col-sm-12';

    var dropdownLabel = document.createElement('label');
    dropdownLabel.textContent = label + ':';

    var dropdown = document.createElement('select');
    var dropdownId = fieldName + Date.now(); // Generate a unique ID for the dropdown
    dropdown.name = fieldName;
    dropdown.id = dropdownId;
    dropdown.required = true;
    dropdown.className = 'form-control';

    // Use AJAX to fetch data from the server
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var materialData = JSON.parse(xhr.responseText);

            // Loop through the fetched data and create options
            materialData.forEach(function (material) {
                var option = document.createElement('option');
                option.value = material.material_id;
                option.textContent = material.name + '(' + material.unit + ') คงเหลือ : ' + material.amount + '';
                dropdown.appendChild(option);
            });
        }
    };

    xhr.open('GET', '../api/admin/dropdownMat.php', true);
    xhr.send();

    dropdownContainer.appendChild(dropdownLabel);
    dropdownContainer.appendChild(dropdown);
    container.appendChild(dropdownContainer);
    return dropdownId;
}

function appendInput(fieldName, label, container, counter) {
    var inputContainer = document.createElement('div');
    inputContainer.className = 'col-sm-12';

    var inputLabel = document.createElement('label');
    inputLabel.textContent = label + ':';

    var input = document.createElement('input');
    input.type = 'number';
    var inputId = fieldName + counter + Date.now(); // Generate a unique ID for the input
    input.name = fieldName + counter; // Update the name with the counter
    input.id = inputId;   // Update the ID with the counter
    input.required = true;
    input.className = 'form-control';

    inputContainer.appendChild(inputLabel);
    inputContainer.appendChild(input);
    container.appendChild(inputContainer);

    return inputId;
}
</script>


<script>
    function closeForm() {
        setTimeout(function () {
                        window.location.replace("http://127.0.0.1:8080/project/admin/mt_used.php");
                    }, 100);
}
    </script>

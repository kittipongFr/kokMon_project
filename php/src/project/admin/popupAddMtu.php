
<!-- //add -->
<style>
    /* Additional styles for the pop-up form */
    .form-popup {
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




<form id="rmtAddForm" class="form-popup">

<div class="close-button " onclick="closeForm()">
<a class="btn btn-danger text-white" >
    X
</a> 
    </div>
    <input type="hidden" name="mem_id" id="mem_id" value="1"> <!-- Replace with your actual value -->
    
  

    <!-- Container for additional sections -->
    <div class="form-group" id="additionalSectionsContainer">
        <!-- Additional sections will be appended here -->
    </div>
    <div class="form-group">
    <div class="d-flex float-end">
            <button class="btn btn-primary" style="float: right;" type="button" id="addMt">เพิ่มรายการ <span class='glyphicon glyphicon-plus'></span></button>
     
            <button class="btn btn-success mx-2" type="button" id="submitBtn">บันทึก</button>
           
        </div>
    </div>
</form>



<script>
var sectionCounter = 1;
var inputSets = []; // Array to store references to input sets

document.getElementById('addMt').addEventListener('click', function () {
    sectionCounter++;
    appendInputSet(sectionCounter);
});

function appendInputSet(counter) {
    var uniqueId = Date.now();

    var newInputContainer = document.createElement('div');
    newInputContainer.className = 'form-group input-set';
    newInputContainer.id = 'inputSet_' + uniqueId;

    var newLabel = document.createElement('div');
    newLabel.className = 'col-sm-12 control-label';
    newLabel.textContent = 'รายการที่ ' + counter + ':';

    var newInput = document.createElement('div');
    newInput.className = 'col-sm-12';

    // Append dropdown
    var dropdownId = appendDropdown('material_id', 'ชื่อวัตถุดิบ(ห้ามเลือกซ้ำ!)', newInput);

    // Append input for amount
    var amountId = appendInput('amount', 'จำนวนวัตถุดิบ', newInput, counter);


    newInputContainer.appendChild(newLabel);
    newInputContainer.appendChild(newInput);


        // Append delete button
        var deleteBtn = document.createElement('button');
    deleteBtn.className = 'btn btn-danger text-white my-2';
    deleteBtn.type = 'button';
    deleteBtn.textContent = 'ลบรายการ';

    deleteBtn.addEventListener('click', function () {
        var container = document.getElementById('additionalSectionsContainer');
        
        // Check if there's at least one input set before attempting to delete
        if (inputSets.length > 0) {
            var lastInputSet = inputSets.pop(); // Remove the last element from the array
            container.removeChild(lastInputSet.container);
            sectionCounter--; // Decrement counter when deleting a set
        }
    });
    newInput.appendChild(deleteBtn);

    newInputContainer.appendChild(newLabel);
    newInputContainer.appendChild(newInput);

    document.getElementById('additionalSectionsContainer').appendChild(newInputContainer);


    // Add the newInputContainer reference to the array
    inputSets.push({
        container: newInputContainer,
        dropdownId: dropdownId,
        amountId: amountId
    });
    console.log(inputSets);
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

// Call appendInputSet function once when the page loads
window.addEventListener('load', function () {
    // Initial call to create one input set
    appendInputSet(1);
});
</script>


<script>
        document.getElementById("openFormBtn").addEventListener("click", function() {
      document.querySelector(".form-popup").style.display = "block";
    });

    function closeForm() {
        setTimeout(function () {
                        window.location.replace("http://127.0.0.1:8080/project/admin/manufacture.php");
                    }, 100);
}


    </script>
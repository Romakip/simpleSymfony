function addFormToCollection($collectionHolderClass) {
    // Get the ul that holds the collection of tags
    let $collectionHolder = $('.' + $collectionHolderClass);

    // Get the data-prototype explained earlier
    let prototype = $collectionHolder.data('prototype');

    // get the new index
    let index = $collectionHolder.data('index');

    let newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    let $newFormLi = $('<li></li>').append(newForm);
    // Add the new form at the end of the list
    $('.add_item_link').before($newFormLi);
    addTagFormDeleteLink($newFormLi);
}

function addTagFormDeleteLink($tagFormLi) {
    let $removeFormButton = $('<button type="button" class="deleteSubcategory">delete</button>');
    $tagFormLi.append($removeFormButton);

    $removeFormButton.on('click', function (e) {
        $tagFormLi.remove();
    })
}

$(document).ready(function(){

    $collectionHolder = $('ul.subcategories');

    $collectionHolder.find('li').each(function() {
        addTagFormDeleteLink($(this));
    })

    let $subcategoriesCollectionHolder = $('ul.subcategories');

    $subcategoriesCollectionHolder.data('index', $subcategoriesCollectionHolder.find('input').length);

    $('body').on('click', '.add_item_link', function(e) {
        let $collectionHolderClass = $(e.currentTarget).data('collectionHolderClass');

        addFormToCollection($collectionHolderClass);
    })

});

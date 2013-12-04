<?php

namespace YourBooks\BookBundle;

final class YourBooksBooksEvents
{
    const AFTER_REGISTER_AUTHOR = "yourbooks_book.register_author";
    const AFTER_BOOK_UPLOAD = "yourbooks_book.book_upload";
    const AFTER_ADMIN_VALIDATION = "yourbooks_book.admin_validation";
    const AFTER_BOOK_ASSIGNED = "yourbooks_book.book_assigned";
    const AFTER_REVIEW_SEND = "yourbooks_book.review_send";
    const AFTER_REVIEW_VALIDATION = "yourbooks_book.review_validation";
}
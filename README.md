# TYPO3 form framework finishers for pushing data to a JobRouter installation

[![Build Status](https://travis-ci.org/brotkrueml/jobrouter_form.svg?branch=master)](https://travis-ci.org/brotkrueml/jobrouter_form)

The extension supports TYPO3 9 LTS.

## Purpose

The extension adds a form finisher to your TYPO3 installation which
enables you to push form data to a JobData table in a [JobRouter](https://www.jobrouter.com)
installation.

Planned is also a form finisher to start a process instance.

The extension is work in progress, the interface can change at any time.


## Installation

The recommended way to install this extension is by using Composer. In your Composer-based TYPO3 project root, just type

    composer req brotkrueml/typo3-jobrouter-form


## Usage

The form finisher can be added to a form. But you have to configure the
mapping of the form fields to the JobData table columns in the yaml file
directly. Example:

    finishers:
        -
            identifier: JobData
            options:
                tableUid: '42'
                columns:
                    company:
                        mapOnFormField: 'companyName'
                    name:
                        mapOnFormField: 'name'
                    email:
                        mapOnFormField: 'emailAddress'
                    message:
                        mapOnFormField: 'message'
                    source:
                        staticValue: 'Contact form from website'

First, you have to define the uid of the table. This is the ID of the
the table record in TYPO3, not the GUID of the JobData table in JobRouter
(as there are constellations where this is not unique). 
 
Under the `columns` key you assign the JobData columns to the form fields
or a static value, respectively. As you can see, a table column name don't
have to be named like the form field name.

**Please note:** You have to assign every column of the JobData table that
the user has access to. This is due to the fact that the underlying rest
resource triggers an error if not every column is sent.

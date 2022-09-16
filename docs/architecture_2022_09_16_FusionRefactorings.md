# Fusion Refactorings

## Problem Description

We want to refactor Eel expressions like `node.context.inBackend` or `site.context.inBackend` towards
`Neos.Ui.NodeInfo.inBackend(node)` and `Neos.Ui.NodeInfo.inBackend(site)` respectively.

This must work BOTH in Eel expressions inside Fusion, and inside AFX blocks.

We cannot rely on the node variables being called `site` or `node`; but we can start refactoring these
and then add comments for all remaining occurences.

## Solution Idea

- [ ] find all Eel expression positions in a file
  - [x] use Fusion parser to find Eel expression positions in Fusion files
  - [x] use Fusion parser to find AFX Expressions in Fusion files
  - [ ] use AFX parser to find Eel Expression positions in AFX Blocks
- [ ] rewrite Eel expressions
  - [ ] do semantically correct transformation
- [ ] add comments where we cannot find the markers.

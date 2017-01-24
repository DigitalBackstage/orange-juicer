<?php

namespace DigitalBackstage\OrangeJuicer\Console;

use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class OrangeJuicerQuestionHelper extends SymfonyQuestionHelper
{
    /**
     * Same as the parent, but does not display choices
     */
    protected function writePrompt(OutputInterface $output, Question $question)
    {
        $text = OutputFormatter::escapeTrailingBackslash($question->getQuestion());
        $default = $question->getDefault();

        switch (true) {
            case null === $default:
                $text = sprintf(' <info>%s</info>:', $text);

                break;

            case $question instanceof ConfirmationQuestion:
                $text = sprintf(' <info>%s (yes/no)</info> [<comment>%s</comment>]:', $text, $default ? 'yes' : 'no');

                break;

            case $question instanceof ChoiceQuestion && $question->isMultiselect():
                $choices = $question->getChoices();
                $default = explode(',', $default);

                foreach ($default as $key => $value) {
                    $default[$key] = $choices[trim($value)];
                }

                $text = sprintf(' <info>%s</info> [<comment>%s</comment>]:', $text, OutputFormatter::escape(implode(', ', $default)));

                break;

            case $question instanceof ChoiceQuestion:
                $choices = $question->getChoices();
                $text = sprintf(' <info>%s</info> [<comment>%s</comment>]:', $text, OutputFormatter::escape($choices[$default]));

                break;

            default:
                $text = sprintf(' <info>%s</info> [<comment>%s</comment>]:', $text, OutputFormatter::escape($default));
        }

        $output->writeln($text);

        // removed code for displaying blocs here

        $output->write(' > ');
    }
}
